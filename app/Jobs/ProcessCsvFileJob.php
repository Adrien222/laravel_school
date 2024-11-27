<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class ProcessCsvFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Lire le fichier CSV
        $data = $this->readCsv($this->filePath);

        // Valider et traiter chaque ligne
        foreach ($data as $index => $row) {
            if ($index === 0) {
                continue; // Ignore la première ligne du csv
            }

            // Valider les données
            $validator = Validator::make($row, [
                0 => 'required|string|max:255', // Validation du nom
                1 => 'required|email|max:255|unique:users,email', // validation du mail
            ]);

            if ($validator->fails()) {
                Log::warning("Données invalides à la ligne {$index}", $row);
                continue;
            }

            // Insérer l'utilisateur
            User::create([
                'name' => $row[0],
                'email' => $row[1],
                'role' => 'client', // Rôle par défaut
            ]);

            Log::info("Utilisateur ajouté : {$row[0]} ({$row[1]})");
        }
    }

    /**
     * Lire le fichier CSV et retourner les données sous forme de tableau.
     */
    private function readCsv(string $path): array
    {
        $file = fopen($path, 'r');
        $data = [];
        while (($line = fgetcsv($file)) !== false) {
            $data[] = $line;
        }
        fclose($file);
        return $data;
    }
}

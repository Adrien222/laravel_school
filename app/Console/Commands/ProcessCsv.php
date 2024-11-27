<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessCsvFileJob;

class ProcessCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:process {filePath : Le chemin du fichier CSV à traiter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatcher un job pour traiter un fichier CSV dans la queue file_processing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('filePath');

        // Vérifier si le fichier existe
        if (!file_exists($filePath)) {
            $this->error("Le fichier spécifié n'existe pas : {$filePath}");
            return 1;
        }

        // Dispatcher le job dans la queue file_processing
        ProcessCsvFileJob::dispatch($filePath)->onQueue('file_processing');

        $this->info("Le fichier {$filePath} a été dispatché dans la queue 'file_processing'.");
        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GenerateCsv extends Command
{
    const DELIMITER = ',';

    const END_LINE_CHARACTER = "\n";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère un csv avec 5000 lignes';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $faker = \Faker\Factory::create();

        $csvFilePath = 'csv/data.csv';
        $csvHeaders = ['Name', 'Email'];

        Storage::put($csvFilePath, implode(self::DELIMITER, $csvHeaders));

        $csvData = '';

        for ($i = 0; $i < 5000; $i++) {
            $csvData .= $faker->unique->name . self::DELIMITER . $faker->unique->email . self::END_LINE_CHARACTER;
        }

        Storage::append($csvFilePath, $csvData);

        $this->info('Fichier créé');
    }
}

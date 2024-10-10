<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Générer 50 clients
        Client::factory()->count(50)->create();
    }
}

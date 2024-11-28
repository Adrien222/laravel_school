<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\Roles;
use App\Events\UserCreated;

class CreateUser extends Command
{
    protected $signature = 'user:create {name} {email} {--role= : Spécifier le rôle de l\'utilisateur (ex: client, admin, super_admin)}';
    protected $description = 'Créer un utilisateur avec un nom, un email, et un rôle';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        $defaultRole = Roles::Client->value;
        $roleValue = $this->option('role');

        if (!$roleValue) {
            if ($this->confirm("Voulez-vous créer un nouvel utilisateur client ?", true)) {
                $roleValue = $defaultRole;
            } else {
                $roleValue = $this->choice(
                    'Quel est le rôle du nouvel utilisateur ?',
                    array_column(Roles::cases(), 'value')
                );
            }
        }

        if (!in_array($roleValue, array_column(Roles::cases(), 'value'))) {
            $this->error("Le rôle spécifié est invalide.");
            return 1;
        }

        // Émettre l'événement UserCreated
        event(new UserCreated($name, $email, $roleValue));

        $this->info("L'événement UserCreated a été émis pour {$name} ({$email}) avec le rôle {$roleValue}.");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Enums\Roles;

class CreateUser extends Command
{
    // Nom de la commande
    protected $signature = 'user:create {name} {email} {--role= : Spécifier le rôle de l\'utilisateur (ex: client, admin, super_admin)}';

    // Description de la commande
    protected $description = 'Créer un utilisateur avec un nom, un email, et un rôle';

    // Exécution de la commande
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        // Définir le rôle par défaut
        $defaultRole = Roles::Client->value;

        // Vérifier si un rôle a été passé en option
        $roleValue = $this->option('role');

        if (!$roleValue) {
            // Demander confirmation pour le rôle par défaut
            if ($this->confirm("Voulez-vous créer un nouvel utilisateur client ?", true)) {
                $roleValue = $defaultRole;
            } else {
                // Si la réponse est non, demander de choisir un rôle
                $roleValue = $this->choice(
                    'Quel est le rôle du nouvel utilisateur ?',
                    array_column(Roles::cases(), 'value')
                );
            }
        }

        // Valider le rôle avec l'énumération
        if (!in_array($roleValue, array_column(Roles::cases(), 'value'))) {
            $this->error("Le rôle spécifié est invalide.");
            return 1;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("Un utilisateur avec cet email existe déjà.");
            return 1;
        }

        // Créer et sauvegarder l'utilisateur
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'role' => $roleValue, // Assigner la valeur de l'énumération au rôle
        ]);

        $this->info("L'utilisateur {$user->name} avec le rôle {$user->role} a été créé avec succès !");
    }
}

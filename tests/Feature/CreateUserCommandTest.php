<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Events\UserCreated;
use Illuminate\Support\Facades\Event;

class CreateUserCommandTest extends TestCase
{
    /**
     * Vérifie que l'événement UserCreated est émis lors de l'exécution de la commande.
     */
    public function test_user_created_event_is_dispatched()
    {
        // Simule l'écoute des événements
        Event::fake();

        // Arguments pour la commande
        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $role = 'client';

        // Exécute la commande
        $this->artisan('user:create', [
            'name' => $name,
            'email' => $email,
            '--role' => $role,
        ])->expectsOutput("L'événement UserCreated a été émis pour {$name} ({$email}) avec le rôle {$role}.")
            ->assertExitCode(0);

        // Vérifie que l'événement UserCreated a été émis avec les bons arguments
        Event::assertDispatched(UserCreated::class, function ($event) use ($name, $email, $role) {
            return $event->name === $name && $event->email === $email && $event->role === $role;
        });
    }

    /**
     * Vérifie que la commande gère un rôle invalide.
     */
    public function test_command_handles_invalid_role()
    {
        // Exécute la commande avec un rôle invalide
        $this->artisan('user:create', [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            '--role' => 'invalid_role',
        ])->expectsOutput('Le rôle spécifié est invalide.')
            ->assertExitCode(1);
    }

    /**
     * Vérifie l'interaction de confirmation pour le rôle par défaut.
     */
    public function test_command_defaults_to_client_role_when_confirmed()
    {
        // Simule l'écoute des événements
        Event::fake();

        // Arguments sans rôle spécifié
        $name = 'Alice Doe';
        $email = 'alicedoe@example.com';

        // Exécute la commande et simule la confirmation
        $this->artisan('user:create', [
            'name' => $name,
            'email' => $email,
        ])->expectsQuestion('Voulez-vous créer un nouvel utilisateur client ?', true)
            ->expectsOutput("L'événement UserCreated a été émis pour {$name} ({$email}) avec le rôle client.")
            ->assertExitCode(0);

        // Vérifie que l'événement UserCreated a été émis avec le rôle par défaut
        Event::assertDispatched(UserCreated::class, function ($event) use ($name, $email) {
            return $event->name === $name && $event->email === $email && $event->role === 'client';
        });
    }
}

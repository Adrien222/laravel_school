<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\User;

class PersistAUser
{
    /**
     * Handle the event.
     *
     * @param UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        if (User::where('email', $event->email)->exists()) {
            throw new \Exception("Un utilisateur avec cet email existe déjà.");
        }

        // Persist l'utilisateur
        User::create([
            'name' => $event->name,
            'email' => $event->email,
            'role' => $event->role,
        ]);
    }
}

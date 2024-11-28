<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserCreated
{
    use Dispatchable;

    public $name;
    public $email;
    public $role;

    /**
     * Créer une nouvelle instance de l'événement.
     *
     * @param string $name
     * @param string $email
     * @param string $role
     */
    public function __construct(string $name, string $email, string $role)
    {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
    }
}

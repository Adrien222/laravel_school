<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;



class AuthenticationService
{
    protected $user;


    public function __construct(User $user)
    {
        // Attribuer la variable user 
        $this->user = $user;
    }

    public function createToken(): string
    {
        // Générer un token aléatoire de 20 caractères
        $token = Str::random(20);

        // Stocker le token et la date de génération dans l'utilisateur
        $this->user->authentication_token = $token;
        $this->user->authentication_token_generated_at = Carbon::now(); // Utilisation de Carbon pour la date actuelle

        $this->user->save();

        return $token;
    }

    public function checkToken(string $token): bool
    {
        // Vérifier si le token correspond
        if ($this->user->authentication_token !== $token) {
            return false;
        }

        // Vérifier si la date de génération du token a moins de 24 heures
        $tokenGeneratedAt = $this->user->authentication_token_generated_at;

        if (Carbon::parse($tokenGeneratedAt)->addHours(24)->isPast()) {
            return false; // Le token a expiré
        }

        return true; // Le token est valide
    }

    
}

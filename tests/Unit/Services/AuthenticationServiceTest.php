<?php

use App\Models\User;
use App\Services\AuthenticationService;
use Illuminate\Support\Carbon;

it('crée un token et le sauvegarde dans l\'utilisateur en base de données', function () {
    // Création manuelle d'un utilisateur
    $user = User::query()->create([
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'authentication_token' => null,
        'authentication_token_generated_at' => null,
    ]);

    // Utilisation du service
    $service = new AuthenticationService($user);
    $token = $service->createToken();

    // Recharge les données de l'utilisateur depuis la base
    $user->refresh();

    // Assertions
    expect($token)->toBeString();
    expect(strlen($token))->toBe(20);
    expect($user->authentication_token)->toBe($token);
});

it('retourne false si la date d\'expiration du token dépasse 24 heures', function () {
    // Création d'un utilisateur avec un token expiré
    $user = User::query()->make([
        'name' => 'Jane Doe',
        'email' => 'janedoe@example.com',
        'authentication_token' => 'expiredtoken1234567890',
        'authentication_token_generated_at' => Carbon::now()->subHours(25),
    ]);

    // Utilisation du service
    $service = new AuthenticationService($user);

    // Assertion
    expect($service->checkToken('expiredtoken1234567890'))->toBeFalse();
});

it('retourne false si le token fourni ne correspond pas à celui stocké', function () {
    // Création d'un utilisateur avec un token valide
    $user = User::query()->create([
        'name' => 'Alice',
        'email' => 'alice@example.com',
        'authentication_token' => 'validtoken1234567890',
        'authentication_token_generated_at' => Carbon::now(),
    ]);

    // Utilisation du service
    $service = new AuthenticationService($user);

    // Assertion
    expect($service->checkToken('wrongtoken1234567890'))->toBeFalse();
});

it('retourne true si le token et la date de validité sont valides', function () {
    // Création d'un utilisateur avec un token valide
    $user = User::query()->create([
        'name' => 'Bob',
        'email' => 'bob@example.com',
        'authentication_token' => 'validtoken1234567890',
        'authentication_token_generated_at' => Carbon::now(),
    ]);

    // Utilisation du service
    $service = new AuthenticationService($user);

    // Assertion
    expect($service->checkToken('validtoken1234567890'))->toBeTrue();
});

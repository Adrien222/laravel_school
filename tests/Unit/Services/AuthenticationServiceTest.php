<?php

use App\Models\User;
use App\Services\AuthenticationService;

it('crée un token et le sauvegarde dans l\'utilisateur en base de données', function () {
    $user = User::factory()->create();
    $service = new AuthenticationService($user);
    $token = $service->createToken();
    $user->refresh();
    expect($token)->toBeString();
    expect(strlen($token))->toBe(20);
    expect($user->authentication_token)->toBe($token);
});

it('retourne false si la date d\'expiration du token dépasse 24 heures', function () {
    $user = User::factory()->withExpiredToken()->create();
    $service = new AuthenticationService($user);
    expect($service->checkToken($user->authentication_token))->toBeFalse();
});

it('retourne false si le token fourni ne correspond pas à celui stocké', function () {
    $user = User::factory()->withValidToken()->create();
    $service = new AuthenticationService($user);
    expect($service->checkToken('wrongtoken1234567890'))->toBeFalse();
});

it('retourne true si le token et la date de validité sont valides', function () {
    $user = User::factory()->withValidToken()->create();
    $service = new AuthenticationService($user);
    expect($service->checkToken($user->authentication_token))->toBeTrue();
});

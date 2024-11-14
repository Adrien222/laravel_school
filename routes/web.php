<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Middleware\Guest;
use App\Http\Middleware\MyMiddlewareCheckExecutionOrder;
use App\Http\Middleware\MyMiddlewareRedirectIfAccessRequest;
use App\Http\Middleware\MyMiddlewareWhitelistIP;
use App\Models\Tool;

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});

// Route de test de requête
Route::get('/request', function (Request $request) {
    return dd($request);
});

// Route pour redirection permanente vers /
Route::get('/redirect', function () {
    return redirect('/')->setStatusCode(301);
});

// Route dynamique pour afficher un nom
Route::get('/name/{name}', function ($name) {
    return "Bonjour, " . ucfirst($name);
});

// Route dynamique pour afficher un ID (numérique uniquement)
Route::get('/ressource/{id}', function ($id) {
    return "L'ID est : " . $id;
})->where('id', '[0-9]+');

// Route de test simple
Route::get('/test', function () {
    return view('test');
});

// Route POST pour un formulaire de test
Route::post('/request', function (Request $request) {
    dd($request->all());
})->name('request');

// Route de test de middleware pour vérifier l'ordre d'exécution
Route::get('/test-middleware', function () {
    return view('test');
})->middleware(MyMiddlewareCheckExecutionOrder::class);

// Route sécurisée par middleware de redirection
Route::get('/secure', function () {
    return 'Vous avez accès à cette page !';
})->middleware(MyMiddlewareRedirectIfAccessRequest::class);

// Routes pour les outils avec ressource limitées
Route::resource('tools', ToolController::class)->only(['index', 'show']);

// Route pour tester l'édition des outils
Route::get('/toolsedit', function () {
    $tools = \App\Models\Tool::all();
    foreach ($tools as $tool) {
        $tool->update([
            'price' => json_encode([
                'amount' => $tool->price,
                'currency' => 'EUR',
                'currency_rate' => rand(0, 100) / 100,
            ])
        ]);
    }
});

// Route pour tester le cast personnalisé dans ToolController
Route::get('/test-cast-controller', [ToolController::class, 'testCast']);

// Route pour tester le filtrage des outils par prix
Route::get('/tools-price', function () {
    $tools = Tool::wherePriceGreaterThan(50)->get();
    dd($tools);
});

// Routes d'authentification Magic Link
Route::get('/auth/login', [AuthenticationController::class, 'showForm'])
    ->name('login')
    ->middleware('guest');
Route::post('/auth/login', [AuthenticationController::class, 'login'])
    ->middleware('guest');
Route::get('/auth/callback', [AuthenticationController::class, 'callback'])
    ->name('authentication.callback')
    ->middleware('guest');
Route::get('/auth/logout', [AuthenticationController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Page d'accueil pour les utilisateurs authentifiés
Route::get('/home', HomeController::class)
    ->middleware('auth');

// Routes pour InvoiceController avec protection d'accès
Route::resource('invoices', InvoiceController::class);

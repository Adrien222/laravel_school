<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Middleware\MyMiddlewareCheckExecutionOrder;
use App\Http\Middleware\MyMiddlewareWhitelistIP;
use App\Http\Middleware\MyMiddlewareRedirectIfAccessRequest;
use App\Http\Controllers\ToolController;
use App\Models\Tool;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/request', function ($request) {
    return dd($request);
});

// Route qui fait une redirection permanente vers /
Route::get('/redirect', function () {
    return redirect('/')->setStatusCode(301);
});


// Route qui affiche le nom dynamique
Route::get('/name/{name}', function ($name) {
    return "Bonjour, " . ucfirst($name);
});

// Route qui affiche l'ID (seulement si c'est un numéro)
Route::get('/ressource/{id}', function ($id) {
    return "L'ID est : " . $id;
})->where('id', '[0-9]+');

//La route test
Route::get('/test', function () {
    return view('test');
});


// La route POST pour le formulaire test
Route::post('/request', function (Request $request) {
    dd($request->all());
})->name('request');

Route::get('/test-middleware', function () {
    return view('test');
    dd('Route exécutée après le middleware');
})->middleware(MyMiddlewareCheckExecutionOrder::class);

Route::get('/secure', function () {
    return 'Vous avez accès à cette page !';
})->middleware(MyMiddlewareRedirectIfAccessRequest::class);




//Route::get('/tools', [ToolController::class, 'index']);

Route::get('/toolsdb', function () {
    $tools = Tool::all(); // Récupère tous les outils dans la base de données
    return view('tools.index', compact('tools'));
});

//Route::get('/tools/{id}', [ToolController::class, 'show']);

Route::resource('tools', ToolController::class)->only(['index', 'show']);

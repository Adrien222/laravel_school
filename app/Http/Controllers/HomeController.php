<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class HomeController extends Controller
{
    public function __invoke (Request $request)
    {
        $email = $request->user()->email;
        $route = route('logout');

        return Blade::render('
            Bravo vous êtes connecté : {{ $email }}
            <br><br>
            <a href="{{ $route }}">Se déconnecter</a>
            ', compact('email', 'route'));
    }
}

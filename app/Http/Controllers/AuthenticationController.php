<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthenticationService;
use Illuminate\Support\Facades\Auth;


class AuthenticationController extends Controller
{
    public function showForm(){
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Aucun utilisateur voila quoi erreur en gros'
            ]);
        }
       


        $user->sendAuthenticationMail();

        return redirect()->back()->with('status', 'Vérifiez votre email pleaaaaase !');
    }

    public function callback(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'redirect_to' => 'required|url',
        ]);


        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'No user found with this email address.',
            ]);
        }

        $authService = new AuthenticationService($user);

        if (!$authService->checkToken($request->token)) {
            return redirect()->route('login')->withErrors([
                'token' => 'Invalid or expired token.',
            ]);
        }

        Auth::login($user);

        return redirect($request->query('redirect_to'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}

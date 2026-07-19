<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // --- LOGIN & LOGOUT ---

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors(['email' => 'Email ou mot de passe incorrect.']);
        }

        return redirect()->to($this->redirectByRole());
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('welcome');
    }

    private function redirectByRole(): string
    {
        return match(Auth::user()->role) {
            'admin'             => route('admin.dashboard'),
            'resp_hebergement'  => route('hebergement.dashboard'),
            'technicien'        => route('technicien.dashboard'),
            'resp_foyer'        => route('foyer.dashboard'),
            'etudiante'         => route('etudiante.dashboard'),
            default             => route('welcome'),
        };
    }

    // --- MOT DE PASSE OUBLIÉ ---

    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Lien de réinitialisation envoyé par email !')
            : back()->withErrors(['email' => 'Impossible d\'envoyer le lien.']);
    }

    // Affiche le formulaire avec le token
    public function showReset(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token, 
            'email' => $request->email
        ]);
    }

    // Traite la mise à jour du mot de passe
    public function resetPassword(Request $request)
    {
        // 1. Validation
         $request->validate([
    'token'    => 'required',
    'email'    => 'required|email',
    'password' => [
        'required',
        'min:8',
        'confirmed',
        'regex:/^[A-Z].*(?:\d.*\d)/',
    ],
], [
    'password.regex' => 'Le mot de passe doit commencer par une majuscule et contenir au moins 2 chiffres.',
]);

        // 2. Tentative de réinitialisation
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        // 3. Redirection
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Votre mot de passe a été réinitialisé avec succès !')
            : back()->withErrors(['email' => 'Le jeton de réinitialisation est invalide ou a expiré.']);
    }
}
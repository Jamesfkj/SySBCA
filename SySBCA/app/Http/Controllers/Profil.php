<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Hash;

class Profil extends Controller
{
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:utilisateurs,email'],
        ], [
            'email.required' => 'L’adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'email.exists' => 'Aucun compte trouvé avec cette adresse email.',
        ]);
        $utilisateur = Utilisateur::where('email', $validated['email'])->first();
        if (!empty($utilisateur)) {
            $utilisateur->password = Hash::make(Str::random(8));
            $utilisateur->etat = 'inactif';
            $utilisateur->remember_token = Str::random(64);
            $utilisateur->save();
            Mail::to($utilisateur->email)->send(new ResetPasswordMail($utilisateur));
            return redirect()
                ->route('login')
                ->with('success', 'Un email de réinitialisation vous a été envoyé. Veuillez vérifier votre boîte de réception.');
        }
    }
}

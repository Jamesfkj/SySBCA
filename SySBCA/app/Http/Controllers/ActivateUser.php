<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ActivateUser extends Controller
{
    public function showActivate($token)
    {
        $user = Utilisateur::where('remember_token', $token)->first();
        if (is_null($user)) {
            abort(404, 'NOT FOUND');
        } else {
            return view(
                'activation',
                [
                    'user' => $user
                ]
            );
        }
    }

    public function defineNewPassword(Request $request, $id)
    {
        $user = Utilisateur::findOrFail($id);
        $request->validate(
            [
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
                
            ],
            [
                'password.required' => 'Le mot de passe est requis.',
                'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
                'password_confirmation.required' => 'La confirmation du mot de passe est requise.',
            ]
        );
        $user->password = Hash::make($request->password);
        $user->etat = 'actif';
        $user->remember_token = null;
        $user->save();
        return redirect()->route('login');
    }
}

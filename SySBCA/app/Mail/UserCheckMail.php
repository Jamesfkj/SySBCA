<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class UserCheckMail extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur;
    public $token;
    public $lienActivation;
    public $password;
    public function __construct($utilisateur, $password)
    {
        $this->password = $password;
        $this->utilisateur = $utilisateur;
        $this->token = $utilisateur->remember_token;
    }

    /**
     * Construit le message.
     */
    public function build(): static
    {
        return $this->subject('Bienvenue sur notre plateforme')
            ->view('userCheckMail')
            ->with([
                'utilisateur' => $this->utilisateur,
                'token' => $this->token,
            ]);
    }
}

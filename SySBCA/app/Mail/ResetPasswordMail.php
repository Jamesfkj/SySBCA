<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur;
    public $token;
    public function __construct($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        $this->token = $utilisateur->remember_token;
    }

    public function build(): static
{
    // On crée le Mailable
    return $this->subject('Réinitialisation de mot de passe !')
        ->view('resetPasswordMail')
        ->with([
            'utilisateur' => $this->utilisateur,
            'token' => $this->token,
        ])
        ->withSwiftMessage(function ($message) {
            $logoCid = $message->embed(public_path('images/pnlp3.jpg'));
        });
}

}

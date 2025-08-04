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
    public $id;
    public $lienActivation;
    public function __construct($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        $this->id = Crypt::encrypt($utilisateur->id);
        $this->lienActivation = url('http://127.0.0.1:8000/login/' . $this->id);
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
                        'lienActivation' => $this->lienActivation,
                    ]);
    }
}

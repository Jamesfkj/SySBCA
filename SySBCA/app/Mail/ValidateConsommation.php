<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ValidateConsommation extends Mailable
{
    use Queueable, SerializesModels;

    public $utilisateur;
    public $conso;
    public $destinataireType;
    public function __construct($utilisateur, $conso)
    {
        $this->utilisateur = $utilisateur;
        $this->conso = $conso;
    }

    public function build(): static
    {
        return $this->subject('Validation de consommation')
            ->view('validateConsommation')
            ->with([
                'utilisateur' => $this->utilisateur,
                'conso' => $this->conso,
            ])->withSwiftMessage(function ($message) {
                $logoCid = $message->embed(public_path('images/pnlp3.jpg'));
            });
    }
}

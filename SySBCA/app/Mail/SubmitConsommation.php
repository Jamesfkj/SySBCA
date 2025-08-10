<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitConsommation extends Mailable
{
    use Queueable, SerializesModels;
    public $utilisateur;
    public $conso;
    public function __construct($utilisateur, $conso)
    {
        $this->utilisateur = $utilisateur;
        $this->conso = $conso;
    }

    public function build(): static
    {
        return $this->subject('Soumission de consommation')
                    ->view('submitConsommation')
                    ->with([
                        'utilisateur' => $this->utilisateur,
                        'conso' => $this->conso,
                    ]);
    }
   
}

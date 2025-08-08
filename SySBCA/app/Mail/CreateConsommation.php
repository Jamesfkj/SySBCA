<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreateConsommation extends Mailable
{
    use Queueable, SerializesModels;
    public $utilisateur;
    public $conso;
    public function __construct($utilisateur, $conso)
    {
        $this->utilisateur = $utilisateur;
        $this->$conso = $conso;
    }

    public function build(): static
    {
        return $this->subject('Création de consommation')
                    ->view('createConsommation')
                    ->with([
                        'utilisateur' => $this->utilisateur,
                        'conso' => $this->conso,
                    ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

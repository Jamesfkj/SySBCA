<?php

namespace App\Services;

use MailerSend\MailerSend;
use MailerSend\Helpers\Builder\Recipient;
use MailerSend\Helpers\Builder\EmailParams;

class MailerSendService
{
    protected MailerSend $mailerSend;

    public function __construct()
    {
        $this->mailerSend = new MailerSend([
            'api_key' => config('services.mailersend.token'),
        ]);
    }

    public function envoyerMail($destinataireEmail, $destinataireNom, $sujet, $utilisateur)
    {
        $recipients = [
            new Recipient($destinataireEmail, $destinataireNom),
        ];

        $emailParams = (new EmailParams())
            ->setFrom(config('services.mailersend.from_email'))
            ->setFromName(config('services.mailersend.from_name'))
            ->setRecipients($recipients)
            ->setSubject($sujet)
            ->setHtml(view('userCheckMail', ['utilisateur' => $utilisateur])->render());

        return $this->mailerSend->email->send($emailParams);
    }
}

<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService
{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ) {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }

    public function sendEmail()
    {
        $email = (new Email())
            ->from('booking@paris-prestige-transfert.fr')
            ->to('chennuo0142@gmail.com')

            ->subject('Test tache planifier!')
            ->text('Email programmer envoyer tous les soir a minuit!')
            ->html('<p>Email programmer envoyer tous les soir a minuit!</p>');


        $this->mailer->send($email);
    }
}

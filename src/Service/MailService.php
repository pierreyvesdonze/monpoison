<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    public function sendCommentMail($comment) {
        $mailer = new MailerInterface();

        $message = (new TemplatedEmail())
            ->from($this->getUser()->getEmail())
            ->to(
                'contact@monpoison.fr',
            )
            ->subject('De la part de ' . $this->getUser()->getPseudo() . ' ! de monpoison.fr')
            ->htmlTemplate('email/comment.notification.html.twig')
            ->context([
                'sender'  => $this->getUSer()->getEmail(),
                'text' => $comment
            ]);

        $mailer->send($message);
    }
}
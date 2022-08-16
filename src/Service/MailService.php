<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Param object $user
     */
    public function sendCommentMail($comment, $user)
    {
        $message = (new TemplatedEmail())
            ->from($user->getEmail())
            ->to(
                'contact@monpoison.fr',
                'pyd3.14@gmail.com'
            )
            ->subject('De la part de ' . $user->getPseudo() . ' ! de monpoison.fr')
            ->htmlTemplate('email/comment.notification.html.twig')
            ->context([
                'sender'  => $user->getEmail(),
                'text' => $comment
            ]);

        $this->mailer->send($message);
    }

    /**
     * @Param string $user
     */
    public function sendContactMail($comment, $user)
    {
        $message = (new TemplatedEmail())
            ->from($user)
            ->to(
                'contact@monpoison.fr',
                'pyd3.14@gmail.com'
            )
            ->subject('De la part de ' . $user . ' ! de monpoison.fr')
            ->htmlTemplate('email/contact.notification.html.twig')
            ->context([
                'sender'  => $user,
                'text' => $comment
            ]);

        $this->mailer->send($message);
    }

    /**
     * @Param contact@monpoison.fr $user
     */
    public function sendTestimonialMail($comment, $user)
    {
        $message = (new TemplatedEmail())
            ->from('contact@monpoison.fr')
            ->to(
                'contact@monpoison.fr',
                'pyd3.14@gmail.com'
            )
            ->subject('De la part de ' . $user . ' ! de monpoison.fr')
            ->htmlTemplate('email/contact.notification.html.twig')
            ->context([
                'sender'  => $user,
                'text' => $comment
            ]);

        $this->mailer->send($message);
    }

    /**
     * @Param string $user
     */
    public function subscribeNotification($user)
    {
        $message = (new TemplatedEmail())
            ->from($user->getEmail())
            ->to(
                'contact@monpoison.fr',
            )
            ->subject('De la part de ' . $user->getPseudo() . ' ! de monpoison.fr')
            ->htmlTemplate('email/subscribe.notification.html.twig')
            ->context([
                'user'  => $user->getEmail()
            ]);

        $this->mailer->send($message);
    }

    public function sendSubscribersNewPost($recipients, $post)
    {
        $message = (new TemplatedEmail())
            ->from('contact@monpoison.fr')
            ->to(
                $recipients
            )
            ->subject('Nouvel article sur monpoison.fr')
            ->htmlTemplate('email/new.post.html.twig')
            ->context(['post'=>$post]);

        $this->mailer->send($message);
    }
}

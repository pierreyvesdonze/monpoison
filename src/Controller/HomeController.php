<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            '' => '',
        ]);
    }

    /**
     * @Route("/conditions/générales/utilisation", name="cgu")
     */
    public function cgu(): Response
    {
        return $this->render('cgu/cgu.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(
        Request $request,
        MailerInterface $mailer
        )
    {

     $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $sender = $form->get('email')->getData();
            $text = $form->get('text')->getData();

            $message = (new TemplatedEmail())
                ->from($sender)
                ->to(
                    'pyd3.14@gmail.com@gmail.com',
                )
                ->subject('De la part de ' . $sender . ' !')
                ->htmlTemplate('email/contact.notification.html.twig')
                ->context([
                    'sender'  => $sender,
                    'text' => $text
                ]);

            $mailer->send($message);

            $this->addFlash('success', 'Votre message a bien été envoyé ! Je vous recontacte dès que possible.');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

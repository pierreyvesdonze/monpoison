<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MailService;

class HomeController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('/', name:'home')]
    public function index(): Response
    {
        $user = $this->getUser();
        if ($user) {
            if (null != $user->getHomepage() && 'home' !== $user->getHomepage()) {
                return $this->redirectToRoute($user->getHomepage());
            }
        }
        return $this->render('home/index.html.twig');
    }

    #[Route('/conditions/générales/utilisation', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('cgu/cgu.html.twig');
    }

    #[Route('/contact', name: 'contact', methods: ['GET', 'POST'])]
    public function contact(
        Request $request,
        MailService $mailer
    ) {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sender = $form->get('email')->getData();
            $text = $form->get('text')->getData();

            // Send email to contact@monpoison.fr
            $mailer->sendContactMail($text, $sender);

            $this->addFlash('success', 'Votre message a bien été envoyé ! Je vous recontacte dès que possible.');
        }

        return $this->render('contact/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('pourquoi/creer/un/compte', name: 'why_register')]
    public function whyRegister()
    {
        return $this->render('home/why.register.html.twig');
    }
}

<?php

namespace App\Controller;

use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailService $mailService
    ) {
    }

    /**
     * @Route("articles/abonnement", name="subscribe_articles")
     */
    public function subscribeToArticles() :Response
    {
        if(!false == $this->getUSer()) {
            $this->getUser()->setIsSubscribed(true);
            $this->em->flush();
            $this->mailService->subscribeNotification($this->getUser());
        }
        
        return $this->render('subscribe/thanks.html.twig');
    }

    /**
     * @Route("articles/désabonnement", name="unsubscribe_articles")
     */
    public function unSubscribeToArticles(): Response
    {
        if (!false == $this->getUSer()) {
            $this->getUser()->setIsSubscribed(false);
            $this->em->flush();
        }

        return $this->render('subscribe/bye.html.twig');
    }
}

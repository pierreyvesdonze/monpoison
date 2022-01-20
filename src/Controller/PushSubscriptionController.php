<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PushSubscriptionController extends AbstractController
{
    #[Route('/push/subscription', name: 'push_subscription')]
    public function index(): Response
    {
        return $this->render('push_subscription/index.html.twig', [
            'controller_name' => 'PushSubscriptionController',
        ]);
    }
   public function key() 
    {
        dd(VAPID::createVapidKeys());
        return [
            'key' => $this->getParameter('app.env')('VAPID_PUBLIC_KEY')
        ];
    }
}

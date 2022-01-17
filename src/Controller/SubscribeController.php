<?php

namespace App\Controller;

use App\Entity\Subscriber;
use App\Repository\SubscriberRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("articles/abonnement/{emailSubscriber}", name="subscribe_posts", options={"expose"=true})
     */
    public function subscribeToPosts(
        $emailSubscriber, 
        Request $request,
        SubscriberRepository $subscriberRepository
        )
    {
        if ($request->isMethod('POST')) {
            $message = null;
            $existingSubscriber = $subscriberRepository->findOneBy([
                'email' => $emailSubscriber
            ]);

            if (false == $existingSubscriber) {
                $newSubscriber = new Subscriber;
                $newSubscriber->setEmail($emailSubscriber);
                
                $this->em->persist($newSubscriber);
                $this->em->flush();

                $message = "Merci pour votre inscription, vous recevrez une notification par email à chaque publication d'un nouvel article !";
            } else {
                $message = 'Vous êtes déjà abonné aux articles et je vous en remercie !';
            }

            $user = $this->getUser();
            
            if(!false == $user && $user->getEmail() == $emailSubscriber) {
                $user->setIsSubscribed(true);
                $this->em->flush();
                $this->mailService->subscribeNotification($user);
            }
        }
        
        return new JsonResponse($message);
    }

    /**
     * @Route("articles/désabonnement/{postId}", name="unsubscribe_posts", options={"expose"=true})
     */
    public function unSubscribeToPosts(
        $postId,
        Request $request,
        SubscriberRepository $subscriberRepository
        ): Response
    {
        $isUnsubscribed = false;

        if ($request->isMethod('POST')) {
            $emailSubscriber = $_POST['unsubscribe-input'];
            $existingSubscriber = $subscriberRepository->findOneBy([
                'email' => $emailSubscriber
            ]);

            if(!false == $existingSubscriber) {
                $isUnsubscribed = true;
            }

            if(false == preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/', $emailSubscriber)) {
                $this->addFlash('danger', "Cette adresse mail est invalide");
                $isUnsubscribed = false;
            } elseif($existingSubscriber) {
                $isUnsubscribed = true;
                $this->em->remove($existingSubscriber);
                $this->addFlash('success', 'Enregistré !');
            } else {
                $this->addFlash('danger', "Cette adresse mail n'est pas enregistrée");
            }

            if (!false == $this->getUSer()) {
                $this->getUser()->setIsSubscribed(false);
                $this->em->flush();
            }
        }


        return $this->render('subscribe/bye.html.twig', [
            'postId' => $postId,
            'isUnsubscribed' => $isUnsubscribed
        ]);
    }
}

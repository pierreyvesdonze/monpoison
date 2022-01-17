<?php

namespace App\Controller;

use App\Entity\Sober;
use App\Form\SoberType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SoberController extends AbstractController
{
    public function __construct(
        private  EntityManagerInterface $em
    ) {
    }
    
    /**
     * @Route("/sobriete/ajouter", name="sober_add")
     */
    public function addSober(Request $request): Response
    {
        $form = $this->createForm(SoberType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $newSober = new Sober;
            $newSober->setUser($this->getUser());
            $newSober->setDate($form->get('date')->getData());
            
            $this->em->persist($newSober);
            $this->em->flush();

            $this->addFlash('success', 'Un jour sobre est un jour noble !');

            return $this->redirectToRoute('drink_calendar');
        }


        return $this->render('sober/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

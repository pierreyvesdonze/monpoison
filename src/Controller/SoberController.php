<?php

namespace App\Controller;

use App\Form\SoberType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SoberController extends AbstractController
{
    
    /**
     * @Route("/sobriete/ajouter", name="sober_add")
     */
    public function addSober(Request $request): Response
    {
        $form = $this->createForm(SoberType::class);
        $form->handleRequest($request);


        return $this->render('sober/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

<?php

namespace App\Controller;

use App\Form\AlcoolTestType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlcoolController extends AbstractController
{
    /**
     * @Route("/alcool/test", name="alcool_test")
     */
    public function setScore(Request $request): Response
    {
        $form = $this->createForm(AlcoolTestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $score           = 0;
            $ageFactor       = $form->get('age')->getData();
            $weightFactor    = $form->get('weight')->getData();
            $lastDayFactor   = count($form->get('lastDay')->getData());
            $lastDrinkFactor = (int)$form->get('lastDrink')->getData();
            $moneyFactor     = (int)$form->get('money')->getData();

            if (0 === $form->get('sex')->getData()) {
                $sexFactor = 1.2;
            } else {
                $sexFactor = 1;
            }

            $score += round(($ageFactor - $weightFactor + ($lastDayFactor *= 10) + $lastDrinkFactor + ($moneyFactor/2)) * $sexFactor);
            
            return $this->redirectToRoute('alcool_test_result', [
                'score' => $score
            ]);
        }     

        return $this->render('alcool/test.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/alcool/test/result/{score}", name="alcool_test_result")
     */
    public function getRestult($score)
    {
        return $this->render('alcool/test.result.html.twig', [
            'score' => $score
        ]);
    }
}

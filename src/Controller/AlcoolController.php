<?php

namespace App\Controller;

use App\Form\AlcoolTestType;
use App\Repository\DrinkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AlcoolController extends AbstractController
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

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

            $score += round(($ageFactor + $weightFactor + ($lastDayFactor *= 10) + $lastDrinkFactor + ($moneyFactor/2)) * $sexFactor);

            $user = $this->getUSer();
            if (null != $user) {
                $user->setAlcoolScore($score);

                $this->entityManager->flush();
            }
            
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
    public function getRestult(int $score)
    {
        /**
         * @var string
         */
        $message = null;

        if ($score <= 80 && $score > 0) {
            $message = "Votre consommation d'alcool est modérée, ne dépassez pas ce stade sous risque de voir votre dépendance au produit augmenter";
        } elseif($score >= 80 && $score <= 110) {
            $message = "Votre consommation d'alccol est nettement supérieure aux recommandations de l'OMS. Rappelons que selon celles-ci, il est préférable de limiter la consommation d'alcool à 2 verres par jour pour une femme et 3 verres pour un homme, avec au moins 2 jours d'abstinence dans la semaine";
        } elseif ($score >= 111 && $score <= 130) {
            $message = "Attention, votre consommation d'alcool dépasse les recommandations établies par l'OMS. À ce stade vous présentez un risque de dépendance à l'alcool moyennement prononcé, il serait recommandé de réduire vos consommations.";
        } elseif ($score >= 131 && $score <= 150) {
            $message = "Votre consommation dépasse largement les recommandations de l'OMS. Vous présentez des signes de dépendance à l'alcool et il serait sage de réduire la cadence sous peine de devenir addict au produit et de voir des problèmes de santé arriver...";
        } elseif ($score > 151) {
            $message = "Alerte ! Votre niveau de consommation d'alcool est bien au delà du raisonnable. À ce stade le risque de dépendance est extrêmement élevé et sur la durée votre santé générale va se dégrader, votre moral va significativement baisser, ainsi vous risquez de nombreux problèmes à tous les niveaux ! Rapprochez-vous de votre médecin pour vous faire aider sans plus attendre !";
        } elseif ($score == 0) {
            $message = "Si vous ne consommez pas d'alcool du tout, vous n'avez aucun risque de devenir dépendant !";
        }

        return $this->render('alcool/test.result.html.twig', [
            'message' => $message,
            'score'   => $score
        ]);
    }
}

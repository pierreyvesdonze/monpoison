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
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @Route("/alcool/test/addiction", name="alcool_test")
     */
    public function setScore(Request $request): Response
    {
        $form = $this->createForm(AlcoolTestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $score                   = 0;
            $frequencyComsumption    = ($form->get('frequencyComsumption')->getData());
            $drinkByDay              = $form->get('drinkByDay')->getData();
            $fiveDrinksFrequency     = $form->get('fiveDrinksFrequency')->getData();
            $stopControl             = $form->get('stopControl')->getData();
            $failAttempt             = $form->get('failAttempt')->getData();
            $needFirstDrink          = $form->get('needFirstDrink')->getData();
            $regrets                 = $form->get('regrets')->getData();
            $noMemory                = $form->get('noMemory')->getData();

            $score += $frequencyComsumption + $drinkByDay + $fiveDrinksFrequency + $stopControl + $failAttempt + $needFirstDrink + $regrets + $noMemory;

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
     * @Route("/alcool/test/addiction/result/{score}", name="alcool_test_result")
     */
    public function getResult(int $score)
    {
        /**
         * @var string
         */
        $message = null;

        if ($score === 0) {
            $message = "Votre consommation d'alcool est nulle ou raisonnable, vous ne courez pas ou très peu de risque de dépendance.";
        } elseif ($score <= 4 && $score > 0) {
            $message = "Votre consommation d'alcool est modérée, ne dépassez pas ce stade au risque de voir votre dépendance au produit augmenter";
        } elseif ($score >= 5 && $score <= 8) {
            $message = "Votre consommation d'alccol est nettement supérieure aux recommandations de l'OMS. Rappelons que selon celles-ci, il est préférable de limiter la consommation d'alcool à 2 verres par jour pour une femme et 3 verres pour un homme, avec au moins 2 jours d'abstinence dans la semaine";
        } elseif ($score >= 9 && $score <= 12) {
            $message = "Attention, votre consommation d'alcool dépasse les recommandations établies par l'OMS. À ce stade vous présentez un risque de dépendance à l'alcool moyennement prononcé, il serait recommandé de réduire vos consommations.";
        } elseif ($score >= 12 && $score <= 16) {
            $message = "Votre consommation dépasse largement les recommandations de l'OMS. Vous présentez des signes de dépendance à l'alcool et il serait sage de réduire la cadence sous peine de devenir addict au produit et de voir des problèmes de santé arriver...";
        } elseif ($score > 16) {
            $message = "Alerte ! Votre niveau de consommation d'alcool est bien au delà du raisonnable. À ce stade le risque de dépendance est extrêmement élevé et sur la durée votre santé générale va se dégrader, votre moral va significativement baisser, ainsi vous risquez de nombreux problèmes à tous les niveaux ! Rapprochez-vous de votre médecin pour vous faire aider sans plus attendre !";
        }

        return $this->render('alcool/test.result.html.twig', [
            'message' => $message,
            'score'   => $score
        ]);
    }

    /**
     * @Route("alcool/stats", name="alcool_stats")
     */
    public function alcoolStats()
    {
        return $this->render('alcool/stats.html.twig');
    }
}

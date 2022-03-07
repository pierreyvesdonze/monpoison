<?php

namespace App\Controller;

use App\Form\AlcoolTestType;
use App\Form\EthylotestType;
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

            $score += ($frequencyComsumption/2) + $drinkByDay + $fiveDrinksFrequency + $stopControl + $failAttempt + $needFirstDrink + $regrets + $noMemory;

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
     * @Route("/alcool/test/addiction/resultat/{score}", name="alcool_test_result")
     */
    public function getAddictResultScore(int $score)
    {
        /**
         * @var string
         */
        $message = null;

        if ($score === 0) {
            $message = "Votre consommation d'alcool est nulle ou raisonnable, vous ne courez pas ou très peu de risque de dépendance.";
        } elseif ($score <= 4 && $score > 0) {
            $message = "Votre consommation d'alcool est modérée, boire davantage et/ou plus souvent peut induire une forme légère de dépendance.";
        } elseif ($score >= 5 && $score <= 8) {
            $message = "Votre consommation d'alccol est nettement supérieure aux recommandations de l'OMS. Selon celles-ci, il est préférable de limiter la consommation d'alcool à 2 verres par jour pour une femme et 3 verres pour un homme, avec au moins 2 jours d'abstinence dans la semaine";
        } elseif ($score >= 9 && $score <= 12) {
            $message = "Attention, votre consommation d'alcool dépasse sensiblement les recommandations établies par l'OMS. À ce stade le risque de dépendance à l'alcool est moyennement prononcé, réduire ses consommations est conseillé.";
        } elseif ($score >= 12 && $score <= 16) {
            $message = "A ce stade les consommations d'alcool sont classées parmis des consommations à risque. Le niveau de dépendance peut s'élever insinueusement mais rapidement. Saviez-vous que 7.1% à 12.6 de la population consommait quotidiennement de l'alcool ? Ce site existe afin d'apporter des solutions pour suivre et ajuster ses consommations et ainsi les contrôler.";
        } elseif ($score > 16) {
            $message = "Attention ! Votre niveau de consommation d'alcool est à un niveau au delà du raisonnable. À ce stade le risque de dépendance est extrêmement élevé. Sur la durée votre santé générale a de grands risques de se dégrader, votre socialisation peut être malmenée et votre moral peut significativement baisser. Rapprochez-vous de votre médecin ou d'un addictologue pour vous permettre d'obtenir des conseils et de l'aide pour garder le contrôle. Plus tôt arrive la conscience que l'on est en danger, plus facilement on peut corriger le tir.";
        }
        return $this->render('alcool/test.result.html.twig', [
            'message' => $message,
            'score'   => $score
        ]);
    }

    /**
     * @Route("alcool/statistiques", name="alcool_stats")
     */
    public function alcoolStats()
    {
        return $this->render('alcool/stats.html.twig');
    }

    /**
     * @Route("/alcool/test/alcoolemie", name="ethylotest")
     */
    public function setEthyloTestScore(Request $request): Response
    {
        $form = $this->createForm(EthylotestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sex      = $form->get('sex')->getData();
            $weight   = $form->get('weight')->getData();
            $quantity = $form->get('quantity')->getData();
            $degree   = $form->get('degree')->getData();

            if (0 === $sex) {
                $score = ((($quantity * 10) * $degree * 0.8) / ($weight * 0.7)) / 52.5;
            } else {
                $score = ((($quantity * 10) * $degree * 0.8) / ($weight * 0.6)) / 36;
            }

            return $this->redirectToRoute('ethylotest_result', [
                'score' => $score
            ]);
        }
        return $this->render('alcool/ethylotest.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/alcool/ethylotest/{score}", name="ethylotest_result")
     */
    public function ethylotestResult($score): Response
    {
        return $this->render('alcool/ethylotest.result.html.twig', [
            'score' => $score
        ]);
    }
}

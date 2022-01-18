<?php

namespace App\Controller;

use App\Entity\Sober;
use App\Form\SoberType;
use App\Repository\SoberRepository;
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
    public function addSober(
        Request $request,
        SoberRepository $soberRepository
        ): Response
    {
        $form = $this->createForm(SoberType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $formDate = $form->get('date')->getData();
    
            if ($formDate = $soberRepository->findByUserAndByDate($user,
                $formDate
            )) {
                $this->addFlash('danger', 'Vous avez déjà été sobre ce jour là');

                return $this->redirectToRoute('drink_calendar');
            }
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

    /**
     * @Route("/sobriete/retirer{soberId}", name="sober_remove")
     */
    public function removeSober(
        $soberId,
        SoberRepository $soberRepository
    ) {
        $soberDay = $soberRepository->findBy([
            'id' => $soberId
        ]);

        $this->em->remove($soberDay[0]);
        $this->em->flush();

        $this->addFlash('success', 'Jour de sobriété retiré !');

        return $this->redirectToRoute('drink_calendar');
    }
}

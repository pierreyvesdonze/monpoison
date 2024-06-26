<?php

namespace App\Controller;

use App\Entity\Sober;
use App\Form\SoberType;
use App\Repository\SoberRepository;
use App\Service\SoberService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SoberController extends AbstractController
{
    public function __construct(
        private  EntityManagerInterface $em,
        private SoberRepository $soberRepository
    ) {
    }

    #[Route('/sobriete/ajouter', name:'sober_add')]
    public function addSober(
        Request $request,
        SoberService $soberService
    ): Response {
        $form = $this->createForm(SoberType::class);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $formDate = $form->get('date')->getData();

            if (true == $soberService->checkExistingDrink($user, $formDate)) {
                $this->addFlash('danger', 'Vous ne pouvez pas ajouter une sobriété le même jour qu\'une consommation');

                return $this->redirectToRoute('drink_calendar');
            }

            if ($formDate = $this->soberRepository->findByUserAndByDate(
                $user,
                $formDate
            )) {
                $this->addFlash('danger', 'Vous avez déjà été sobre ce jour là : ' . $formDate->getDate()->format('d/m/y'));

                return $this->redirectToRoute('drink_calendar');
            }

            $newSober = new Sober();
            $newSober->setUser($user);
            $newSober->setDate($form->get('date')->getData());

            $this->em->persist($newSober);
            $this->em->flush();

            $this->addFlash('success', 'Un jour sobre est un jour noble !');

            return $this->redirectToRoute('money_add');
        }

        return $this->render('sober/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('sobriete/retirer/{soberId}', name:'sober_remove')]
    public function removeSober(
        $soberId
    ) {
        $soberDay = $this->soberRepository->findBy([
            'id' => $soberId
        ]);

        $this->em->remove($soberDay[0]);
        $this->em->flush();

        $this->addFlash('success', 'Jour de sobriété retiré !');

        return $this->redirectToRoute('drink_calendar');
    }

    #[Route('/sobriete/ajouter/auto', name:'sober_add_auto', options: ['expose' => true])]
    public function addAutoSober(SoberService $soberService)
    {
        $soberService->addAutoSoberDay($this->getUser());

        return new JsonResponse('ok');
    }
}

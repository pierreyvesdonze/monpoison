<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private EmailVerifier $emailVerifier,
        private EntityManagerInterface $em
    ) {
    }

    #[Route('/register', name:'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        UserRepository $userRepository
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            if (strlen($form->get('plainPassword')->getData()) < 6) {
                $this->addFlash('danger', 'Votre mot de passe doit contenir 6 caractères');
            }
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles($user->getRoles());
            $user->setIsSubscribed(false);
            $user->setIsDeleted(false);
            $user->setAutoSober(true);

            $this->em->persist($user);
            $this->em->flush();

            // generate a signed url and email it to the user
            // $this->emailVerifier->sendEmailConfirmation(
            //     'app_verify_email',
            //     $user,
            //     (new TemplatedEmail())
            //         ->from(new Address('admin@monpoison.fr', 'pyd'))
            //         ->to($user->getEmail())
            //         ->subject("NE PAS REPONDRE confirmation d'email")
            //         ->htmlTemplate('registration/confirmation_email.html.twig')
            // );
            // do anything else you need here, like send an email

            return $this->redirectToRoute('login');
        } else {
            $checkIfUserExists = $userRepository->checkIfExists($form->get('email')->getData());

            if (true == $checkIfUserExists) {
                $this->addFlash('danger', 'Un compte associé à cet email existe déjà.');

                return $this->redirectToRoute('login');
            }
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name:'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    #[Route('/user/delete', name:'delete_user')]
    public function deleteUser(): Response
    {
        $this->em->remove($this->getUser());
        $this->em->flush();

        $this->container->get('security.token_storage')->setToken(null);
        $this->addFlash('success', 'Votre compte a bien été supprimé !');

        return $this->redirectToRoute('logout');
    }
}

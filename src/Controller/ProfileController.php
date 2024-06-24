<?php

namespace App\Controller;

use App\Form\ProfileEdit\ProfileEditEmailType;
use App\Form\ProfileEdit\ProfileEditPasswordType;
use App\Form\ProfileEdit\ProfileEditPictureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * This ProfileController manages the user profile edition.
 * Users can change their profile picture, email and password.
 */
#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * ProfileController constructor.
     *
     * @param EntityManagerInterface $em
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Displays the user profile edition forms.
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/profile', name: 'app.profile')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof PasswordAuthenticatedUserInterface) {
            return $this->redirectToRoute('app.login');
        }

        $emailForm = $this->createForm(ProfileEditEmailType::class, $user);
        $passwordForm = $this->createForm(ProfileEditPasswordType::class, $user);
        $pictureForm = $this->createForm(ProfileEditPictureType::class, $user);
        $emailForm->handleRequest($request);
        $passwordForm->handleRequest($request);
        $pictureForm->handleRequest($request);

        if ($emailForm->isSubmitted() && $emailForm->isValid()) {
            /** @var \App\Entity\User $user */
            $user->setEmail($emailForm->get('email')->getData());

            $this->em->flush();
            $this->addFlash('success', 'Votre adresse email a été mise à jour avec succès !');
            return $this->redirectToRoute('app.profile');
        }

        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $hashedPassword = $this->passwordHasher
                ->hashPassword($user, $passwordForm
                    ->get('plainPassword')
                    ->getData());
            /** @var \App\Entity\User $user */
            $user->setPassword($hashedPassword);

            $this->em->flush();
            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès !');
            return $this->redirectToRoute('app.profile');
        }

        if ($pictureForm->isSubmitted() && $pictureForm->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Votre image de profil a été mise à jour avec succès !');
            return $this->redirectToRoute('app.profile');
        }

        return $this->render('profile/index.html.twig', [
            'editEmailForm' => $emailForm,
            'editPasswordForm' => $passwordForm,
            'editPictureForm' => $pictureForm,
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * This SecurityController manages the login and logout features.
 * Everybody have access to these routes.
 */
class SecurityController extends AbstractController
{
    /**
     * Displays the login page with the sign up form.
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    #[Route(path: '/login', name: 'app.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if user is already logged in, don't display the login page again
        if ($this->getUser()) {
            return $this->redirectToRoute('home.index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // $errorMessage = $error ? 'Email ou mot de passe invalide' : null;

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Logout route.
     *
     * @return void
     */
    #[Route(path: '/logout', name: 'app.logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method can be blank - it will be 
        intercepted by the logout key on your firewall.'
        );
    }
}

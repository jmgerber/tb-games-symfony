<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This HomeController displays the homepage.
 * Everybody have access to this route.
 */
class HomeController extends AbstractController
{
    /**
     * Displays the homepage.
     *
     * @return Response
     */
    #[Route('/', name: 'home.index')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }
}

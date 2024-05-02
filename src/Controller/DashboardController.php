<?php

namespace App\Controller;

use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * This DashboardController displays the games list.
 * Only Users and Admins have access to this route.
 */
#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    /**
     * Displays a list of all escape games.
     *
     * @param GamesRepository $repository
     *
     * @return Response
     */
    #[Route('/dashboard', name: 'app.dashboard')]
    public function index(GamesRepository $repository): Response
    {
        return $this->render(
            'dashboard/index.html.twig',
            [
                'games' => $repository->findAll()
            ]
        );
    }
}

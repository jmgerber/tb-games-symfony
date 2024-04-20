<?php

namespace App\Controller;

use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app.dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(GamesRepository $repository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'games' => $repository->findAll()
        ]);
    }
}

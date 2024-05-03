<?php

namespace App\Controller\API;

use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


/**
 * This GamesControllerAPI returns serialized game data.
 */
#[IsGranted('ROLE_USER')]
class GamesControllerAPI extends AbstractController
{
    /**
     * Returns the list of serialized game data.
     * 
     * @param GamesRepository $repository
     * 
     * @return JsonResponse
     */
    #[Route('/api/games')]
    public function index(GamesRepository $repository): JsonResponse
    {
        $games = $repository->findAll();

        return $this->json($games, 200, [], [
            'groups' => ['games.show']
        ]);
    }
}

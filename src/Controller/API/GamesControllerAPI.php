<?php

namespace App\Controller\API;

use App\Entity\Games;
use App\Repository\GamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * This GamesControllerAPI returns serialized game data.
 */
#[IsGranted('ROLE_USER')]
class GamesControllerAPI extends AbstractController
{
    /**
     * Returns serialized data of all escape games.
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

    /**
     * Returns serialized data of a specific escape game.
     *
     * @param Games $game
     *
     * @return JsonResponse
     */
    #[Route(
        '/api/game/{id}',
        requirements: ['id' => Requirement::DIGITS]
    )]
    public function handleGame(Games $game): JsonResponse
    {
        return $this->json($game, 200, [], [
            'groups' => ['games.show']
        ]);
    }
}

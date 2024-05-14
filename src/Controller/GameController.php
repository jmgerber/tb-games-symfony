<?php

namespace App\Controller;

use App\Entity\Games;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * This GameController returns the game data.
 */
class GameController extends AbstractController
{
    /**
     * Displays the active game page.
     *
     * @param Games $game
     *
     * @return Response
     */
    #[Route(
        '/game/{id}',
        name: 'game.play',
        methods: ['GET', 'POST'],
        requirements: ['id' => Requirement::DIGITS]
    )]
    public function index(Games $game): Response
    {
        if (!$game) {
            $this->redirectToRoute('app.dashboard');
        }

        $firstRiddle = $game->getRiddle()[0];

        return $this->render(
            'game/index.html.twig',
            [
                'game' => $game,
                'firstRiddle' => $firstRiddle
            ]
        );
    }
}

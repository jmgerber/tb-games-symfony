<?php

namespace App\Controller\API;

use App\Entity\Riddle;
use App\Repository\RiddleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * This RiddlesControllerAPI returns serialized riddles data.
 */
#[IsGranted('ROLE_USER')]
class RiddlesControllerAPI extends AbstractController
{
    private RiddleRepository $riddleRepository;

    public function __construct(RiddleRepository $riddleRepository)
    {
        $this->riddleRepository = $riddleRepository;
    }

    /**
     * Returns serialized data of a specific riddle.
     *
     * @param Riddle $riddle
     *
     * @return JsonResponse
     */
    #[Route(
        '/api/riddle/{id}',
        requirements: ['id' => Requirement::DIGITS]
    )]
    public function handleRiddle(Riddle $riddle): JsonResponse
    {
        return $this->json($riddle, 200, [], [
            'groups' => ['riddle.show']
        ]);
    }


    /**
     * Verify a riddle answer and returns serialized data with the message, the next riddle ID and
     * the "success" property to know if the answer is right or wrong.
     *
     * @param Request $request
     * @param Riddle $riddle
     *
     * @return JsonResponse
     */
    #[Route(
        '/api/riddle/{id}/answer',
        methods: 'POST',
        requirements: ['id' => Requirement::DIGITS]
    )]
    public function handleRiddleResponse(Request $request, Riddle $riddle): JsonResponse
    {
        $jsonData = json_decode($request->getContent(), true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $playerAnswer = $jsonData['answer'] ?? null;
        } else {
            // Handle JSON decoding error
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'Invalid request format'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }

        $responseData = [
            'success' => false,
            'message' => '',
            'nextRiddleId' => null
        ];

        if ($playerAnswer === $riddle->getAnswer()) {
            // Check if there are more enigmas
            $nextRiddle = $this->getNextRiddle($riddle);
            $responseData['message'] = 'Félicitations, vous avez résolu l\'énigme !';

            if ($nextRiddle) {
                $responseData['success'] = true;
                $responseData['nextRiddleId'] = $nextRiddle->getId();
            } else {
                // Handle the end of the game
                $responseData['success'] = true;
                $responseData['message'] = 'Félicitations, vous avez terminé l\'escape game !';
            }
        } else {
            $responseData['message'] = 'Mauvaise réponse, essayez encore !';
        }

        // Return a JSON response with the data
        return new JsonResponse($responseData);
    }

    /**
     * Gets the currentRiddle and returns the next riddle if it exists.
     *
     * @param Riddle $currentRiddle
     *
     * @return ?Riddle
     */
    private function getNextRiddle(Riddle $currentRiddle): ?Riddle
    {
        $game = $currentRiddle->getGame();

        $allRiddles = $this->riddleRepository->findByGame($game);
        $currentRiddleIndex = array_search($currentRiddle, $allRiddles);

        if (isset($allRiddles[$currentRiddleIndex + 1])) {
            return $allRiddles[$currentRiddleIndex + 1];
        }

        return null;
    }
}

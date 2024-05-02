<?php

namespace App\Controller\Admin;

use App\Entity\Games;
use App\Form\GamesType;
use App\Repository\GamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * This GamesController manages the list of escape games, their creation,
 * edition and deletion.
 * Only Admins have access to these routes.
 */
#[Route('/admin/games', name: 'admin.games.')]
#[IsGranted('ROLE_ADMIN')]
class GamesController extends AbstractController
{
    /**
     * Displays a list of all escape games.
     *
     * @param GamesRepository $repository
     *
     * @return Response
     */
    #[Route(name: 'index')]
    public function index(GamesRepository $repository): Response
    {
        return $this->render(
            'admin/games/index.html.twig',
            [
                'games' => $repository->findAll()
            ]
        );
    }

    /**
     * Creates a new escape game.
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $game = new Games();
        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($game);
            $em->flush();
            $this->addFlash('success', 'L\'escape game a été créé avec succès');
            return $this->redirectToRoute('admin.games.index');
        }

        return $this->render(
            'admin/games/create.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    /**
     * Edits an existing escape game.
     * If no game is found with the provided id, we create a new Game object
     *
     * @param Request                $request
     * @param Games                  $game
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'edit',
        methods: ['GET', 'POST'],
        requirements: ['id' => Requirement::DIGITS]
    )]
    public function edit(
        Request $request,
        Games $game,
        EntityManagerInterface $em
    ): Response {
        if (!$game) {
            $game = new Games(); // Create a null object
        }

        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash(
                'success',
                'L\'escape game a été modifié avec succès'
            );

            return $this->redirectToRoute('admin.games.index');
        }

        return $this->render(
            'admin/games/edit.html.twig',
            [
                'game' => $game,
                'form' => $form,
            ]
        );
    }

    /**
     * Deletes an existing escape game.
     *
     * @param Games                  $game
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'delete',
        methods: ['DELETE'],
        requirements: ['id' => Requirement::DIGITS]
    )]
    public function delete(Games $game, EntityManagerInterface $em): Response
    {
        $em->remove($game);
        $em->flush();
        $this->addFlash('success', 'L\'escape game a bien été supprimé !');
        return $this->redirectToRoute('admin.games.index');
    }
}

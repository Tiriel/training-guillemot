<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/movie')]
class MovieController extends AbstractController
{
    #[Route('', name: 'app_movie_index')]
    public function index(MovieRepository $repository, Request $request): Response
    {
        $limit = 12;
        $page = $request->query->getInt('page', 1);

        return $this->render('movie/index.html.twig', [
            'movies' => $repository->findBy([], ['id' => 'DESC'], $limit, $limit * ($page - 1))
        ]);
    }

    #[Route('/{id}', name: 'app_movie_show', requirements: ['id' => '\d+'])]
    public function show(?Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    #[Route('/new', name: 'app_movie_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_movie_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function newMovie(?Movie $movie): Response
    {
        $movie ??= new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        return $this->render('movie/new.html.twig', [
            'form' => $form,
        ]);
    }
}

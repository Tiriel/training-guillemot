<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route(
        '',
        name: 'app_book_index',
        methods: ['GET'],
        //condition: 'request.headers.get("x-symfony-header") == "foo"'
    )]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::index',
        ]);
    }

    #[Route('/{id}',name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
    //#[Route('/book/{id<\d+>?1}', name: 'app_book_show', priority: 1)]
    public function show(int $id): Response
    {
        return $this->render('book/show.html.twig', [
            'controller_name' => 'BookController::show - id : '.$id,
        ]);
    }
}

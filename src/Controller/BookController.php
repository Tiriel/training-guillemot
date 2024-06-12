<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(BookRepository $repository, Request $request): Response
    {
        $limit = 20;
        $page = $request->query->get('page', 1);

        $books = $repository->findBy([], ['id' => 'DESC'], $limit, $limit * ($page - 1));

        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::index',
            'books' => $books,
        ]);
    }

    #[Route('/{!id}',name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
    //#[Route('/book/{id<\d+>?1}', name: 'app_book_show', priority: 1)]
    public function show(int $id, BookRepository $repository): Response
    {
        $book = $repository->find($id);

        return $this->render('book/show.html.twig', [
            'controller_name' => 'BookController::show - id : '.$book->getId(),
        ]);
    }

    #[Route('/new', name: 'app_book_new')]
    public function newBook(BookRepository $repository): Response
    {
        $book = (new Book())
            ->setTitle('1984')
            ->setAuthor('Georges Orwell')
            ->setPlot('This is basically now')
            ->setCover('http://foo')
            ->setEditedAt(new \DateTimeImmutable('01-01-1951'))
            ->setEditor('Pocket')
            ->setIsbn('913-1234567-123')
        ;

        $repository->save($book, true);

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }
}

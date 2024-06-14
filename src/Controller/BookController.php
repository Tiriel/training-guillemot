<?php

namespace App\Controller;

use App\Book\BookManager;
use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Security\Voter\BookCreatedVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        $limit = 9;
        $page = $request->query->get('page', 1);

        $books = $repository->findBy([], ['id' => 'DESC'], $limit, $limit * ($page - 1));

        return $this->render('book/index.html.twig', [
            'books' => $books,
            'count' => ceil($repository->count() / $limit),
        ]);
    }

    #[Route('/{!id}',name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1], methods: ['GET'])]
    //#[Route('/book/{id<\d+>?1}', name: 'app_book_show', priority: 1)]
    public function show(?Book $book): Response
    {

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[IsGranted('ROLE_OMDB')]
    #[Route('/title/{title}', name: 'app_book_title', methods: ['GET'])]
    public function title(string $title, BookManager $manager): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_OMDB');
        $book = $manager->findByTitle($title);

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/new', name: 'app_book_new')]
    #[Route('/{id}/edit', name: 'app_book_edit', requirements: ['id' => '\d+'])]
    public function newBook(?Book $book, Request $request, BookRepository $repository): Response
    {
        if ($book) {
            $this->denyAccessUnlessGranted(BookCreatedVoter::CREATED, $book);
        }

        $book ??= new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if ($user instanceof User && !$book->getCreatedBy()) {
                $book->setCreatedBy($user);
            }
            $repository->save($book, true);

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}

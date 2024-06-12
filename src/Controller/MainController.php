<?php

namespace App\Controller;

use App\Dto\Contact;
use App\Form\ContactType;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('', name: 'app_main_index')]
    public function index(MovieRepository $repository): Response
    {
        $movies = $repository->findBy([], ['id' => 'DESC'], 6);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'index',
            'movies' => $movies,
        ]);
    }

    #[Route('/contact', name: 'app_main_contact')]
    public function contact(): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        return $this->render('main/contact.html.twig', [
            'form' => $form,
        ]);
    }
}

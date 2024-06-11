<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class SongController
{
    #[Route('/song', name: 'app_song_index')]
    public function __invoke(Request $request): Response
    {
        return new Response('Yay, song!');
    }
}

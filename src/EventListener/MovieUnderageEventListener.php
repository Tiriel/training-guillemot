<?php

namespace App\EventListener;

use App\Event\MovieUnderageEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class MovieUnderageEventListener
{
    #[AsEventListener(event: MovieUnderageEvent::class)]
    public function onMovieUnderageEvent(MovieUnderageEvent $event): void
    {
        // ...
    }
}

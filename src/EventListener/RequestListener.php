<?php

namespace App\EventListener;

use App\Controller\BookController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

final class RequestListener
{
    public function __construct(
        protected readonly Environment $twig,
        #[Autowire(param: 'env(bool:APP_SHUTDOWN)')]
        protected readonly bool $isShutDown,
    )
    {
    }

    #[AsEventListener(event: RequestEvent::class, priority: 0)]
    public function __invoke(RequestEvent $event): void
    {
        if (!$this->isShutDown) {
            return;
        }

        $request = $event->getRequest();
        if (str_starts_with($request->attributes->get('_controller'), BookController::class)) {
            $response = new Response();
            if ($event->isMainRequest()) {
                $response->setContent($this->twig->render('temporary_shutdown.html.twig'));
            }

            $event->setResponse($response);
        }
    }
}

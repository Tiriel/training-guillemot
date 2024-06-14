<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event): void
    {
        // ...
    }

    public function onBeforeKernelView(ViewEvent $event): void
    {

    }

    public function onAfterKernelView(ViewEvent $event): void
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            ViewEvent::class => [
                ['onBeforeKernelView', 10],
                ['onAfterKernelView', -10],
            ]
        ];
    }
}

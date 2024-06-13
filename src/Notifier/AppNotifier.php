<?php

namespace App\Notifier;

use App\Entity\User;
use App\Notifier\Factory\NotificationFactoryInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class AppNotifier
{
    public function __construct(
        protected readonly ChatterInterface $notifier,
        /** @var NotificationFactoryInterface[] $factories */
        #[TaggedIterator('app.notification_factory', defaultIndexMethod: 'getIndex')]
        protected iterable $factories,
    ) {
        $this->factories = $factories instanceof \Traversable ? iterator_to_array($factories) : $factories;
    }

    public function sendNotification(string $message, User $user)
    {
        $recipient = new Recipient($user->getEmail());
        $notification = $this
            ->factories[$user->getPreferredChannel()]
            ->createNotification($message, $recipient);
        $this->notifier->send($notification);
    }
}

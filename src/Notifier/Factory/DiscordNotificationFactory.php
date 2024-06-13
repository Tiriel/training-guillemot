<?php

namespace App\Notifier\Factory;

use App\Notifier\Notification\DiscordNotification;
use Symfony\Component\Notifier\Bridge\Discord\DiscordOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class DiscordNotificationFactory implements NotificationFactoryInterface
{

    public function createNotification(string $message, Recipient $recipient): ChatMessage
    {
        $options = (new DiscordOptions());

        return (new DiscordNotification())->asChatMessage($recipient)->options($options);
    }

    public static function getIndex(): string
    {
        return 'discord';
    }
}

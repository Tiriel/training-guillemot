<?php

namespace App\Notifier\Factory;

use App\Notifier\Notification\SlackNotification;
use Symfony\Component\Notifier\Bridge\Slack\SlackOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Recipient\Recipient;

class SlackNotificationFactory implements NotificationFactoryInterface
{

    public function createNotification(string $message, Recipient $recipient): ChatMessage
    {
        $options = (new SlackOptions());
        return (new SlackNotification($message))->asChatMessage($recipient)->options($options);
    }

    public static function getIndex(): string
    {
        return 'slack';
    }
}

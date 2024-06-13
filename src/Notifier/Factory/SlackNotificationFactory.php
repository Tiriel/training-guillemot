<?php

namespace App\Notifier\Factory;

use App\Notifier\Notification\SlackNotification;
use Symfony\Component\Notifier\Bridge\Slack\Block\SlackActionsBlock;
use Symfony\Component\Notifier\Bridge\Slack\Block\SlackSectionBlock;
use Symfony\Component\Notifier\Bridge\Slack\SlackOptions;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Recipient\Recipient;

class SlackNotificationFactory implements NotificationFactoryInterface
{

    public function createNotification(string $message, Recipient $recipient): ChatMessage
    {
        $options = (new SlackOptions())
            ->block((new SlackSectionBlock())
                ->text($message)
            )
            ->block((new SlackActionsBlock())
                ->button(
                    'Symfony documentation',
                    'https://www.symfony.com/docs',
                    'primary'
                )
            )
        ;
        return (new SlackNotification($message))->asChatMessage($recipient)->options($options);
    }

    public static function getIndex(): string
    {
        return 'slack';
    }
}

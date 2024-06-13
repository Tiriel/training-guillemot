<?php

namespace App\Notifier\Factory;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Recipient\Recipient;

#[AutoconfigureTag('app.notification_factory')]
interface NotificationFactoryInterface
{
    public function createNotification(string $message, Recipient $recipient): ChatMessage;

    public static function getIndex(): string;
}

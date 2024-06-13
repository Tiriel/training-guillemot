<?php

namespace App\Controller;

use App\Entity\User;
use App\Notifier\AppNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SlackController extends AbstractController
{
    #[Route('/slack/{message}', name: 'app_slack')]
    public function index(string $message, AppNotifier $notifier): JsonResponse
    {
        $user = new class extends User {
            public function getEmail(): ?string
            {
                return 'test@test.com';
            }

            public function getPreferredChannel(): ?string
            {
                return 'slack';
            }
        };
        $notifier->sendNotification($message, $user);

        return $this->json([
            'message' => 'Notification sent',
        ]);
    }
}

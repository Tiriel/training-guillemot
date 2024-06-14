<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class BookCreatedVoter implements VoterInterface
{
    public const CREATED = 'book.created';

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (self::CREATED !== $attributes[0] || !$subject instanceof Book) {
            return self::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        return match ($subject->getCreatedBy() === $user) {
            true => self::ACCESS_GRANTED,
            false => self::ACCESS_DENIED,
        };
    }
}

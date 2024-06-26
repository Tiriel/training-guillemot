<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Event\MovieUnderageEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class MovieRatedVoter implements VoterInterface
{
    public const RATED = 'movie.rated';

    public function __construct(protected readonly EventDispatcherInterface $dispatcher)
    {
    }

    /**
     * @inheritDoc
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        if (!$subject instanceof Movie || self::RATED !== $attributes[0]) {
            return self::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (!$user instanceof User) {
            return self::ACCESS_DENIED;
        }

        $vote = match ($subject->getRated()) {
            'G' => true,
            'PG', 'PG-13' => $user->getAge() && $user->getAge() >= 13,
            'R', 'NC-17' => $user->getAge() && $user->getAge() >= 17,
            default => false
        };

        if (false === $vote) {
            $this->dispatcher->dispatch(new MovieUnderageEvent($subject, $user));
        }

        return true === $vote ? self::ACCESS_GRANTED : self::ACCESS_DENIED;
    }
}

<?php

namespace App\Security\Voter;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class AdminVoter implements VoterInterface
{
    public function __construct(protected readonly RoleHierarchyInterface $hierarchy)
    {
    }

    /**
     * @inheritDoc
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();
        $roles = $this->hierarchy->getReachableRoleNames($user->getRoles());

        if (\in_array('ROLE_ADMIN', $roles)) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}

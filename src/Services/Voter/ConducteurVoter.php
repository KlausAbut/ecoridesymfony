<?php

namespace App\Services\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ConducteurVoter extends Voter
{
    public const CONDUCTEUR = 'conducteur';

    protected function supports(string $attribute, mixed $subject): bool
    {
       return $attribute === self::CONDUCTEUR;     
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return $user instanceof User && $user->isConducteur();
    }
}
<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class VoitureVoter extends Voter
{
    public const EDIT = 'edit_voiture';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::EDIT && $subject instanceof Voiture;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Voiture $voiture */
        $voiture = $subject;

        return $voiture->getUser() === $user;
    }
}


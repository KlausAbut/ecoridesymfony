<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Covoiturage;
use App\Enum\CovoiturageStatut;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CovoiturageVoter extends Voter
{
    const SHOW = 'show';
    const EDIT = 'edit';
    const VALIDATE = 'validate';

    public function __construct(private readonly Security $security) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::SHOW, self::EDIT, self::VALIDATE])) {
            return false;
        }
        if(!$subject instanceof Covoiturage) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }


        // you know $subject is a Post object, thanks to `supports()`
        /** @var Covoiturage $covoiturage */
        $covoiturage = $subject;

        return match($attribute) {
            self::SHOW => $this->canShow($covoiturage, $user),
            self::EDIT => $this->canEdit($covoiturage, $user),
            self::VALIDATE => $this->canValidate($covoiturage, $user),
            default => throw new \LogicException('This code should not be reached!')
        };

    }

    private function canShow(Covoiturage $covoiturage, ?User $user): bool
    {
        if ($covoiturage->getStatut() === CovoiturageStatut::DRAFT && $covoiturage->getCreatedBy() !== $user) {
            return false;
        }

        
        return true;
    }

    private function canEdit(?Covoiturage $covoiturage, ?User $user): bool
    {
        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if (!$covoiturage || !$user) {
            return true;
        }

        return $covoiturage->getCreatedBy() === $user;
    }

    private function isPublished(Covoiturage $covoiturage, User $user)
    {
       return CovoiturageStatut::PUBLISHED === $covoiturage->getStatut();
    }

    private function canValidate(Covoiturage $covoiturage, ?User $user): bool
    {
        if (!$user || !$covoiturage->getParticipants()->contains($user)) {
            return false;
        }

        return $covoiturage->getStatut() === CovoiturageStatut::TERMINE;
    }

}
<?php

namespace App\Security\Voter;

use App\Entity\Review;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ReviewVoter extends Voter
{
    const EDIT = 'REVIEW_EDIT';
    const DELETE = 'REVIEW_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $collection): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && $collection instanceof Review;
    }

    protected function voteOnAttribute(string $attribute, $review, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit();
            case self::DELETE:
                return $this->canDelete();
        }

        return false;
    }

    private function canEdit(): bool
    {
        return $this->security->isGranted('ROLE_PRODUCT_ADMIN');
    }

    private function canDelete(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}

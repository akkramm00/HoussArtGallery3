<?php

namespace App\Security\Voter;

use App\Entity\Contact;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ContactVoter extends Voter
{
    const EDIT = 'CONTACT_EDIT';
    const DELETE = 'CONTACT_DELETE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $collection): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && $collection instanceof Contact;
    }

    protected function voteOnAttribute(string $attribute, $contact, TokenInterface $token): bool
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

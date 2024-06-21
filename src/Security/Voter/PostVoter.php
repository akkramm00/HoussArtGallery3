<?php

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;


class PostVoter extends Voter
{
    const EDIT = 'POST_EDIT';
    const DELETE = 'POST_DELETE';

    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    protected function supports(string $attribute, $post): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && $post instanceof Post;
    }

    protected function voteOnAttribute(string $attribute, $post, TokenInterface $token): bool
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
        return $this->security->isGranted('ROLE_POST_ADMIN');
    }

    private function canDelete(): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
}

<?php

namespace App\Security\Voter;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

//#[AutoconfigureTag(name: 'security.voter', priority: 300)]
class BookVoter extends Voter
{
    public const EDIT = 'book.edit';
    private AuthorizationCheckerInterface $checker;

    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    protected function supports(string $attribute, $subject)
    {
        return $subject instanceof Book && \in_array($attribute, [self::EDIT]);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var Book $subject */
        return $this->checker->isGranted('ROLE_ADMIN')
            ?? $token->getUser() === $subject->getCreatedBy();
    }
}
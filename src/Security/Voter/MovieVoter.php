<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieVoter extends Voter
{
    public const EDIT = 'movie.edit';
    public const VIEW = 'movie.view';
    private AuthorizationCheckerInterface $checker;

    public function __construct(AuthorizationCheckerInterface $checker)
    {
        $this->checker = $checker;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return \in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        if ($this->checker->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->checkView($user, $subject);
            case self::EDIT:
                return $this->checkEdit($user, $subject);
            default:
                return false;
        }
    }

    private function checkView(User $user, Movie $movie): bool
    {
        if (\in_array($movie->getRated(), ['G', 'Not Rated'])) {
            return true;
        }

        $age = $user->getBirthday()
            ? $user->getBirthday()->diff(new \DateTimeImmutable())->y
            : null;

        if (!$age) {
            return false;
        }

        switch ($movie->getRated()) {
            case 'PG':
            case 'PG-13':
                return $age >= 13;
            case 'R':
            case 'NC-17':
                return $age >= 17;
            default:
                return false;
        }
    }

    private function checkEdit(User $user, Movie $movie): bool
    {
        return $this->checkView($user, $movie) && $user === $movie->getCreatedBy();
    }
}
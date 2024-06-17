<?php

declare(strict_types=1);

namespace Apb\UserBundle\Manager;

use Apb\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

abstract class AbstractManager
{
    protected ?User $user = null;

    public function __construct(
        private readonly TokenStorageInterface  $tokenStorage,
    ) {
        $token = $this->tokenStorage->getToken();

        /* @phpstan-ignore-next-line */
        if ($token && $token->getUser() && !\is_string($token->getUser())) {
            $user = $token->getUser();

            /** @var User $user */
            $this->user = $user;
        }
    }
}

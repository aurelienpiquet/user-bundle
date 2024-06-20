<?php

declare(strict_types=1);

namespace Apb\UserBundle\EventListener;

use Apb\UserBundle\Entity\User;
use Apb\UserBundle\Manager\UserManager;
use Apb\UserBundle\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserResolveListener
{
    public function __construct(
        protected UserProviderInterface $userProvider,
        protected UserPasswordHasherInterface $userPasswordHasher,
        protected Security $security,
        protected UserManager $userManager,
        protected UserRepository $userRepository,
    ) {
    }

    /**
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    public function onUserResolve(UserResolveEvent $event): void
    {
        $user = $this->userRepository->fetchByEmail($event->getUsername());

        if (!$user) {
            return;
        }

        try {
            /** @var User $user */
            $user = $this->userProvider->loadUserByIdentifier($user->getUserIdentifier());
        } catch (AuthenticationException $e) {
            return;
        }

        if (!($user instanceof PasswordAuthenticatedUserInterface)) {
            return;
        }
        $event->setUser($user);
    }
}

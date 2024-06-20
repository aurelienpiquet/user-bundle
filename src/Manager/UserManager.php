<?php

declare(strict_types=1);

namespace Apb\UserBundle\Manager;

use App\Entity\User;
use Apb\UserBundle\Form\LoginType;
use Apb\UserBundle\Form\RegisterCreateType;
use Apb\UserBundle\Model\LoginModel;
use Apb\UserBundle\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserManager extends AbstractManager
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly FormFactoryInterface        $formFactory,
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly ParameterBagInterface $bag,
    ) {
        parent::__construct(
            $this->tokenStorage,
        );
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $encoded = $this->hasher->hashPassword($user, $newHashedPassword);
        $user->setPassword($encoded);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function get(): User
    {
        return $this->fetch($this->user->getId());
    }


    /**
     * @throws NonUniqueResultException
     */
    public function fetch(string $id): User
    {
        $user = $this->userRepository->fetch($id);

        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $user;
    }

    /**
     * @param mixed[] $data
     * @return User|FormInterface
     * @throws \Exception
     */
    public function register(array $data): User|FormInterface
    {
        $user = new User();

        $form = $this->formFactory->create(RegisterCreateType::class, $user);

        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        $user->addRole('ROLE_USER');

        $password = $user->getPassword();

        $this->upgradePassword($user, $user->getPassword());

        $this->userRepository->save($user, true);

        try {
            $configuration = $this->bag->get('mailer_bundle.mailer');

            $context = [
                'title' => 'Bienvenu sur ' . $configuration['projectName'],
                'button' => 'Welcome',
                'url' => 'https://google.fr',
                'message' => sprintf("Merci d'avoir créer votre compte sur %s, vous pouvez désormais vous connecter avec vos identifiants <br> identifiant : %s <br> password: %s",
                    $configuration['projectName'],
                    $user->getEmail(),
                    $password,
                )
        ];

            $this->mailer->send($user->getEmail(), $context);
        } catch (\Exception) {}


        return $user;
    }

    /**
     * @param mixed[] $data
     *
     * @return User|FormInterface
     *
     * @throws NonUniqueResultException
     */
    public function login(array $data): User|FormInterface
    {
        $login = new LoginModel();

        $form = $this->formFactory->create(LoginType::class, $login);

        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        $user = $this->userRepository->fetchByEmail($login->getUsername());

        if (!$user || !$this->hasher->isPasswordValid($user, $login->getPassword())) {
            throw new AccessDeniedException();
        }

        return $user;
    }
}

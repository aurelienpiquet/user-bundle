<?php

declare(strict_types=1);

namespace Apb\UserBundle\Manager;

use Apb\UserBundle\Entity\User;
use Apb\UserBundle\Enum\ErrorEnum;
use Apb\UserBundle\Form\PasswordEditType;
use Apb\UserBundle\Form\RequestPasswordCreateType;
use Apb\UserBundle\Model\ResetPasswordModel;
use Apb\UserBundle\Service\MailerService;
use Apb\UserBundle\Form\LoginType;
use Apb\UserBundle\Form\RegisterCreateType;
use Apb\UserBundle\Model\LoginModel;
use Apb\UserBundle\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserManager extends AbstractManager
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly FormFactoryInterface        $formFactory,
        private readonly UserRepository              $userRepository,
        private readonly UserPasswordHasherInterface $hasher,
        private readonly ParameterBagInterface $bag,
        private readonly MailerService $mailer,
        private readonly TranslatorInterface $translator,
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

        if ($this->bag->has('mailer_bundle.configuration')) {
            $configuration = $this->bag->get('mailer_bundle.configuration');

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
        }

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

    /**
     * @param mixed[] $data
     *
     * @return FormInterface|mixed[]
     *
     * @throws NonUniqueResultException
     * @throws \Exception
     */
    public function requestForgottenPassword(array $data): FormInterface|array
    {
        $resetModel = new ResetPasswordModel();

        $form = $this->formFactory->create(RequestPasswordCreateType::class, $resetModel);

        $form->submit($data);

        if (!$form->isValid()) {
            return $form;
        }

        $user = $this->fetchByEmail($resetModel->getEmail());

        if ($user->getResetPasswordAt() && ((new DateTime())->getTimestamp() - ($user->getResetPasswordAt()->getTimestamp()) < 86400)) {
            $form->addError(new FormError(ErrorEnum::REQUEST_PASSWORD_ALREADY_EXIST));

            return $form;
        }

        $user
            ->setResetPasswordToken((string) Uuid::v7())
            ->setResetPasswordAt(new DateTime())
        ;

        $context = [
            'title' => $this->translator->trans('email.forgotten-password.title'),
            'message' => $this->translator->trans('email.forgotten-password.message'),
            'url' => $resetModel->getRedirect(),
            'button' => $this->translator->trans('email.forgotten-password.button'),
        ];

        $this->mailer->send($user->getEmail(), $context);

        $this->userRepository->save($user, true);

        return [];
    }

    /**
     * @param mixed[] $data
     *
     * @return mixed[]|FormInterface
     *
     * @throws NonUniqueResultException
     */
    public function changePassword(array $data, string $token): array|FormInterface
    {
        $user = $this->fetchByRequestPassword($token);

        return $this->updatePassword($user, $data);
    }

    public function updatePassword(User $user, array $data): array|FormInterface
    {
        $form = $this->formFactory->create(PasswordEditType::class, $user);

        $form->submit($data, false);

        if (!$form->isValid()) {
            return $form;
        }

        if (isset($data['password'])) {
            if (!$this->hasher->isPasswordValid($user, $form->get('password')->getData())) {
                $form->addError(new FormError(ErrorEnum::ERROR_PASSWORD));

                return $form;
            }
        }

        if (!isset($data['newPassword'])) {
            $form->addError(new FormError(ErrorEnum::CONSTRAINT_NOT_NULL));

            return $form;
        }

        $user
            ->setResetPasswordToken(null)
            ->setResetPasswordAt(null)
        ;

        $this->upgradePassword($user, $form->get('newPassword')->getData());

        $this->userRepository->save($user, true);

        return [];
    }

    /**
     * @throws NonUniqueResultException
     */
    public function fetchByEmail(string $email): User
    {
        $user = $this->userRepository->fetchByEmail($email);

        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }

    /**
     * @throws NonUniqueResultException
     */
    private function fetchByRequestPassword(string $token): User
    {
        $user = $this->userRepository->fetchByToken($token);

        if (!$user) {
            throw new NotFoundHttpException('Token is not valid.');
        }

        return $user;
    }
}

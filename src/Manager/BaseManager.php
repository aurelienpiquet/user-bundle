<?php

declare(strict_types=1);

namespace Apb\UserBundle\Manager;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BaseManager extends AbstractManager
{
    public function __construct(
        protected FormFactoryInterface $formFactory,
        protected TokenStorageInterface $tokenStorage,
    ) {
        parent::__construct(
            $this->tokenStorage
        );
    }
}

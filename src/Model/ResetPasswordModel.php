<?php

declare(strict_types=1);

namespace Apb\UserBundle\Model;

use Apb\UserBundle\Entity\User;

final class ResetPasswordModel
{
    private ?string $email;

    private ?string $redirect;

    public function __construct(
    ) {
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRedirect(): ?string
    {
        return $this->redirect;
    }

    public function setRedirect(?string $redirect): ResetPasswordModel
    {
        $this->redirect = $redirect;
        return $this;
    }
}

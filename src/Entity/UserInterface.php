<?php

namespace Apb\UserBundle\Entity;

use DateTime;

/**
 * Represents the interface that all user must implement
 *
 * @author Aurelien Piquet <apiquet@feelity.fr>
 */
interface UserInterface
{
    public function getEmail(): ?string;

    public function getLastName(): ?string;

    public function getFirstName(): ?string;

    public function getRole(): ?string;

    public function getResetPasswordAt(): ?DateTime;

    public function getResetPasswordToken(): ?string;

    public function eraseCredentials(): void;
}
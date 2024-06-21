<?php

declare(strict_types=1);

namespace Apb\UserBundle\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use function Symfony\Component\String\u;

#[MappedSuperclass]
#[UniqueEntity('email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups(['apb_user', 'apb_user_list'])]
    protected ?string $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['apb_user', 'apb_user_list'])]
    protected ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['apb_user', 'apb_user_list'])]
    protected ?string $lastName = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['apb_user', 'apb_user_list'])]
    protected ?string $firstName = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    protected ?string $password = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['apb_user', 'apb_user_list'])]
    protected array $roles = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTime $resetPasswordAt = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $resetPasswordToken = null;

    public function __toString()
    {
        if ($this->getFirstName() && $this->getLastName()) {
            return u($this->getFirstName() . ' ' . $this->getLastName())->title(true)->toString();
        }

        return $this->getEmail();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getUserIdentifier(): string
    {
        return $this->getId();
    }

    public function addRole(string $role): static
    {
        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function getResetPasswordAt(): ?DateTime
    {
        return $this->resetPasswordAt;
    }

    public function setResetPasswordAt(?DateTime $resetPasswordAt): User
    {
        $this->resetPasswordAt = $resetPasswordAt;
        return $this;
    }

    public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $resetPasswordToken): User
    {
        $this->resetPasswordToken = $resetPasswordToken;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }
}

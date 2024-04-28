<?php

namespace App\Dashboard\Client\Domain\Agregates;

use App\Dashboard\Client\Domain\Agregates\Exceptions\PasswordNotEqualsException;
use App\Shared\Domain\ValueObject\Uid\UuidValueObject;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticatedClient extends Client implements UserInterface, PasswordAuthenticatedUserInterface
{
    private UuidValueObject $id;

    private array $roles;

    private ClientPlainPassword $plainPassword;

    private ClientPlainPassword $repeatedPlainPassword;

    private ClientHashedPassword $lastHashedPassword;

    private ClientHashedPassword $hashedPassword;

    private ClientProvider $provider;

    private bool $active;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];

        $this->createdAt = new \DateTimeImmutable();
        $this->active = true;
    }

    public static function register(
        string $name,
        string $surname,
        string $email,
        string $plainPassword,
        string $repeatedPlainPassword,
        ?string $secondSurname
    ) {
        $self = new self();

        $self->id = new ClientId();
        $self->name = $name;
        $self->surname = $surname;
        $self->secondSurname = $secondSurname;
        $self->email = new ClientEmail($email);

        $self->plainPassword = new ClientPlainPassword($plainPassword);
        $self->repeatedPlainPassword = new ClientPlainPassword($repeatedPlainPassword);

        if (!$self->plainPassword->equals($self->repeatedPlainPassword)) {
            throw new PasswordNotEqualsException();
        }

        $self->provider = new ClientProvider(ClientProvider::APPLICATION);

        return $self;
    }

    public static function registerWithGoogle(
        string $name,
        string $surname,
        string $email,
        ?string $secondSurname
    ) {
        $self = new self();

        $self->id = new ClientId();
        $self->name = $name;
        $self->surname = $surname;
        $self->secondSurname = $secondSurname;
        $self->email = new ClientEmail($email);

        $self->provider = new ClientProvider(ClientProvider::GOOGLE);

        return $self;
    }

    public function update(
        string $name,
        string $surname,
        string $secondSurname,
        string $email,
    ) {
        $this->name = $name;
        $this->surname = $surname;
        $this->secondSurname = $secondSurname;
        $this->email = new ClientEmail($email);

        return $this;
    }

    public function updatePassword(
        string $newPlainPassword,
        string $repeatedNewPlainPassword,
        string $hashedPassword,
    ) {
        $this->plainPassword = new ClientPlainPassword($newPlainPassword);
        $this->repeatedPlainPassword = new ClientPlainPassword($repeatedNewPlainPassword);

        if (!$this->plainPassword->equals($this->repeatedPlainPassword)) {
            throw new PasswordNotEqualsException();
        }

        $this->hashedPassword = $hashedPassword;

        return $this;
    }

    /**
     * Get the value of id.
     */
    public function getUserIdentifier(): string
    {
        return $this->id->value();
    }

    /**
     * Get the value of hashedPassword.
     */
    public function getPassword(): string
    {
        return $this->hashedPassword->value();
    }

    /**
     * Get the value of hashedPassword.
     */
    public function setPassword(ClientHashedPassword $hashedPassword): self
    {
        $this->hashedPassword = $hashedPassword;

        return $this;
    }

    /**
     * Get the value of provider.
     */
    public function isApplicationProvider(): string
    {
        return ClientProvider::APPLICATION === $this->provider->value();
    }

    /**
     * Get the value of provider.
     */
    public function isGoogleProvider(): string
    {
        return ClientProvider::GOOGLE === $this->provider->value();
    }

    /**
     * Get the value of active.
     */
    public function active(): bool
    {
        return $this->active;
    }

    /**
     * Get the value of roles.
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
        // $this->hashedPassword = new ClientHashedPassword('');
    }
}

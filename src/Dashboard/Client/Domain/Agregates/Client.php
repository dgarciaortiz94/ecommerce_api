<?php

namespace App\Dashboard\Client\Domain\Agregates;

use App\Shared\Domain\Agregate\AgregateRoot;

class Client extends AgregateRoot
{
    protected string $name;

    protected string $surname;

    protected ?string $secondSurname;

    protected ClientEmail $email;

    protected \DateTimeImmutable $createdAt;

    protected \DateTimeImmutable $updatedAt;

    /**
     * Get the value of name.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the value of surname.
     */
    public function surname(): string
    {
        return $this->surname;
    }

    /**
     * Get the value of secondSurname.
     */
    public function secondSurname(): ?string
    {
        return $this->secondSurname;
    }

    /**
     * Get the value of email.
     */
    public function email(): string
    {
        return $this->email->value();
    }

    /**
     * Get the value of createdAt.
     */
    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Get the value of updatedAt.
     */
    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}

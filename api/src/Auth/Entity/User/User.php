<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;

class User
{
    private Id $id;
    private DateTimeImmutable $date;
    private Email $email;
    private string $passwordHash;
    private ?Token $joinConfirmToken;

    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Email $email
     * @param string $passwordHash
     * @param null|Token $joinConfirmToken
     */
    public function __construct(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        ?Token $joinConfirmToken
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->joinConfirmToken = $joinConfirmToken;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function joinConfirmToken(): ?Token
    {
        return $this->joinConfirmToken;
    }

    public function passwordHash(): string
    {
        return $this->passwordHash;
    }
}

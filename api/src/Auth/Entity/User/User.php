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
    private Status $status;
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
        $this->status = Status::wait();
        $this->joinConfirmToken = $joinConfirmToken;
    }

    public function confirmJoin(string $token, DateTimeImmutable $date): void
    {
        if (null === $this->joinConfirmToken) {
            throw new \DomainException('Подтверждение не требуется.');
        }

        $this->joinConfirmToken->validate($token, $date);
        $this->status = Status::active();
        $this->joinConfirmToken = null;
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
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

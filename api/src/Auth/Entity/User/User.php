<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use ArrayObject;
use DateTimeImmutable;

class User
{
    private Id $id;
    private DateTimeImmutable $date;
    private Email $email;
    private Status $status;
    private ArrayObject $networks;
    private ?string $passwordHash = null;
    private ?Token $joinConfirmToken = null;

    /**
     * @param Id $id
     * @param DateTimeImmutable $date
     * @param Email $email
     * @param Status $status
     */
    public function __construct(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        Status $status
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->networks = new ArrayObject();
    }

    public static function requestJoinByNetwork(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        NetworkIdentity $networkIdentity
    ): self {
        $user = new self($id, $date, $email, Status::active());
        $user->networks->append($networkIdentity);

        return $user;
    }

    public static function requestJoinByEmail(
        Id $id,
        DateTimeImmutable $date,
        Email $email,
        string $passwordHash,
        Token $token
    ): self {
        $user = new self($id, $date, $email, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->joinConfirmToken = $token;

        return $user;
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

    public function passwordHash(): ?string
    {
        return $this->passwordHash;
    }

    /**
     * @return NetworkIdentity[]
     */
    public function networks(): array
    {
        return $this->networks->getArrayCopy();
    }

    public function attachNetwork(NetworkIdentity $networkIdentity): void
    {
        foreach ($this->networks() as $network) {
            if (true === $network->isEqualTo($networkIdentity)) {
                throw new \DomainException('Соц. сеть уже привязана к аккаунту пользователя');
            }
        }

        $this->networks->append($networkIdentity);
    }
}

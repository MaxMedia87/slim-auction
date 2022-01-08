<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Auth\Service\PasswordHashGenerator;
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
    private ?Token $passwordResetToken = null;
    private ?Token $newEmailToken = null;
    private ?Email $newEmail = null;
    private Role $role;

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
        $this->role = Role::user();
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

    public function requestPasswordReset(Token $token, DateTimeImmutable $date): void
    {
        if (false === $this->isActive()) {
            throw new \DomainException('Пользователь не активен.');
        }

        if (null !== $this->passwordResetToken() && false === $this->passwordResetToken->isExpiredTo($date)) {
            throw new \DomainException('Запрос на сброс пароля уже отправлен.');
        }

        $this->passwordResetToken = $token;
    }

    public function passwordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    public function resetPassword(string $token, DateTimeImmutable $date, string $hash): void
    {
        if (null === $this->passwordResetToken()) {
            throw new \DomainException('Не отправлен запрос на сброс пароля.');
        }

        $this->passwordResetToken()->validate($token, $date);
        $this->passwordResetToken = null;
        $this->passwordHash = $hash;
    }

    public function changePassword(
        string $currentPassword,
        string $newPassword,
        PasswordHashGenerator $passwordHashGenerator
    ): void {
        if (null === $this->passwordHash()) {
            throw new \DomainException('У пользователя нет пароля.');
        }

        if (false === $passwordHashGenerator->validate($currentPassword, $this->passwordHash())) {
            throw new \DomainException('Вы ввели неверный пароль.');
        }

        $this->passwordHash = $passwordHashGenerator->hash($newPassword);
    }

    public function requestEmailChanging(Token $token, DateTimeImmutable $date, Email $email): void
    {
        if (false === $this->isActive()) {
            throw new \DomainException('Пользователь не активен.');
        }

        if (true === $this->email()->isEqualTo($email)) {
            throw new \DomainException('Е-mail совпадает с текущим.');
        }

        if (null !== $this->newEmailToken() && false === $this->newEmailToken()->isExpiredTo($date)) {
            throw new \DomainException('Изменение E-mail уже запрошено.');
        }

        $this->newEmail = $email;
        $this->newEmailToken = $token;
    }

    public function confirmEmailChanging(string $token, DateTimeImmutable $date): void
    {
        if (null === $this->newEmail() || null === $this->newEmailToken()) {
            throw new \DomainException('Не отправлен запрос на смену Email.');
        }

        $this->newEmailToken()->validate($token, $date);
        $this->email = $this->newEmail();
        $this->newEmailToken = null;
        $this->newEmail = null;
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

    public function changeRole(Role $role): void
    {
        $this->role = $role;
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

    public function newEmailToken(): ?Token
    {
        return $this->newEmailToken;
    }

    public function newEmail(): ?Email
    {
        return $this->newEmail;
    }

    public function role(): Role
    {
        return $this->role;
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

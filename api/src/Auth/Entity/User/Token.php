<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use Webmozart\Assert\Assert;

class Token
{
    private string $value;
    private DateTimeImmutable $expires;

    /**
     * @param string $value
     * @param DateTimeImmutable $expires
     */
    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);

        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function expires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function validate(string $value, DateTimeImmutable $expires): void
    {
        if (false === $this->isEqualTo($value)) {
            throw new \DomainException('Токен невалидный.');
        }

        if (true === $this->isExpiredTo($expires)) {
            throw new \DomainException('Время действия токена истекло.');
        }
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }
}

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
}

<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class Email
{
    private string $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value, 'E-mail адрес обязателен для заполнения.');

        if (false === filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Email адрес "%s" не валиден.', $value));
        }

        $this->value = mb_strtolower($value);
    }

    public function isEqualTo(self $email): bool
    {
        return $this->value() === $email->value();
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}

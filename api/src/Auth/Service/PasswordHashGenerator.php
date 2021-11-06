<?php

declare(strict_types=1);

namespace App\Auth\Service;

use Webmozart\Assert\Assert;

class PasswordHashGenerator
{
    private int $memoryCost;

    public function __construct(int $memoryCost = PASSWORD_ARGON2_DEFAULT_MEMORY_COST)
    {
        $this->memoryCost = $memoryCost;
    }

    public function hash(string $password): string
    {
        Assert::notEmpty($password, 'Пароль обязателен для заполнения.');

        /** @var string|false|null $hash */
        $hash = password_hash($password, PASSWORD_ARGON2I);

        if (null === $hash) {
            throw new \RuntimeException('Невалидный алгоритм хеширования.');
        }

        if (false === $hash) {
            throw new \RuntimeException('Невозможно сгенерировать хеш.');
        }

        return $hash;
    }

    public function validate(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}

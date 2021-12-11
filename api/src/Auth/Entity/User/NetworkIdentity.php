<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Webmozart\Assert\Assert;

class NetworkIdentity
{
    private string $identity;
    private string $network;

    public function __construct(string $identity, string $network)
    {
        Assert::notEmpty($identity, 'ID социальной сети не должен быть пустым.');
        Assert::notEmpty($network, 'Название социальной сети не должен быть пустым.');

        $this->identity = $identity;
        $this->network = $network;
    }

    public function identity(): string
    {
        return $this->identity;
    }

    public function network(): string
    {
        return $this->network;
    }

    public function isEqualTo(self $networkIdentity): bool
    {
        return $this->network() === $networkIdentity->network()
            && $this->identity() === $networkIdentity->identity();
    }
}

<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

class Command
{
    private string $userId;
    private string $identity;
    private string $network;

    public function __construct(string $userId, string $identity, string $network)
    {
        $this->userId = $userId;
        $this->identity = $identity;
        $this->network = $network;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function identity(): string
    {
        return $this->identity;
    }

    public function network(): string
    {
        return $this->network;
    }
}

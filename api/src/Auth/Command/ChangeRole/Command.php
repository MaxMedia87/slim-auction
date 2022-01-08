<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeRole;

class Command
{
    private string $userId;
    private string $role;

    public function __construct(string $userId, string $role)
    {
        $this->userId = $userId;
        $this->role = $role;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function role(): string
    {
        return $this->role;
    }
}

<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

class Command
{
    private string $userId;
    private string $currentPassword;
    private string $newPassword;

    public function __construct(string $userId, string $currentPassword, string $newPassword)
    {
        $this->userId = $userId;
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function currentPassword(): string
    {
        return $this->currentPassword;
    }

    public function newPassword(): string
    {
        return $this->newPassword;
    }
}

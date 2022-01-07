<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Auth\Entity\User\Email;

class Command
{
    private string $userId;
    private Email $email;

    public function __construct(string $userId, Email $email)
    {
        $this->userId = $userId;
        $this->email = $email;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function email(): Email
    {
        return $this->email;
    }
}

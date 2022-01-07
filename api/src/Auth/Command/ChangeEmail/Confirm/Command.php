<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

class Command
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function token(): string
    {
        return $this->token;
    }
}

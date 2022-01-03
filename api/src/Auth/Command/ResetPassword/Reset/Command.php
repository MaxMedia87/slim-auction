<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;

class Command
{
    private Token $token;
    private string $password;

    public function __construct(Token $token, string $password)
    {
        $this->token = $token;
        $this->password = $password;
    }

    public function token(): Token
    {
        return $this->token;
    }

    public function password(): string
    {
        return $this->password;
    }
}

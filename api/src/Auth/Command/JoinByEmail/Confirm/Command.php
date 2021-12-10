<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;

use App\Auth\Entity\User\Token;

class Command
{
    private Token $token;

    public function __construct(Token $token)
    {
        $this->token = $token;
    }

    public function token(): Token
    {
        return $this->token;
    }
}

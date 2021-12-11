<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Entity\User\Email;

class Command
{
    private Email $email;
    private string $network;
    private string $identity;

    public function __construct(Email $email, string $network, string $identity)
    {
        $this->email = $email;
        $this->network = $network;
        $this->identity = $identity;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function network(): string
    {
        return $this->network;
    }

    public function identity(): string
    {
        return $this->identity;
    }
}

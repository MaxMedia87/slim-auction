<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeRole;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\Role;
use App\Auth\Entity\User\UserRepositoryInterface;

class Handler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository()->get(new Id($command->userId()));

        $user->changeRole(
            new Role($command->role())
        );
    }

    public function userRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }
}

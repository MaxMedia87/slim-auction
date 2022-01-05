<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangePassword;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepositoryInterface;
use App\Auth\Service\PasswordHashGenerator;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private PasswordHashGenerator $passwordHashGenerator;

    public function __construct(UserRepositoryInterface $userRepository, PasswordHashGenerator $passwordHashGenerator)
    {
        $this->userRepository = $userRepository;
        $this->passwordHashGenerator = $passwordHashGenerator;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository()->get(new Id($command->userId()));

        $user->changePassword(
            $command->currentPassword(),
            $command->newPassword(),
            $this->passwordHashGenerator()
        );
    }

    public function userRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    public function passwordHashGenerator(): PasswordHashGenerator
    {
        return $this->passwordHashGenerator;
    }
}

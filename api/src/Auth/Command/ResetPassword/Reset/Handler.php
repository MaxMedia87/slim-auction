<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Reset;

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

    public function handle(Command $command)
    {
        $user = $this->userRepository()->findByPasswordResetToken($command->token()->value());

        if (null === $user) {
            throw new \DomainException('Токен не найден.');
        }

        $user->resetPassword(
            $command->token()->value(),
            new \DateTimeImmutable(),
            $this->passwordHashGenerator()->hash($command->password())
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

<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Confirm;

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
        $user = $this->userRepository->findByConfirmToken($command->token);

        if (null === $user) {
            throw new \DomainException('Некорректный токен.');
        }
    }
}

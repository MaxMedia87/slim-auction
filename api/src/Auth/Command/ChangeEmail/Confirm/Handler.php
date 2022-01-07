<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Confirm;

use App\Auth\Entity\User\UserRepositoryInterface;

class Handler
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function userRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    public function handler(Command $command)
    {
        $user = $this->userRepository()->findByNewEmailToken($command->token());

        if (null === $user) {
            throw new \DomainException('Некорректный токен.');
        }

        $user->confirmEmailChanging($command->token(), new \DateTimeImmutable());
    }
}

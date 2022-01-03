<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepositoryInterface;
use App\Auth\Service\TokenGenerator;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private TokenGenerator $tokenGenerator;


    public function __construct(
        UserRepositoryInterface $userRepository,
        TokenGenerator $tokenGenerator
    ) {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function handle(Command $command)
    {
        $email = new Email($command->email());

        $user = $this->userRepository()->getByEmail($email);

        $date = new \DateTimeImmutable();

        $token = $this->tokenGenerator()->generate($date);

        $user->requestPasswordReset($token, $date);
    }

    public function userRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    public function tokenGenerator(): TokenGenerator
    {
        return $this->tokenGenerator;
    }
}

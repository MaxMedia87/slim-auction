<?php

declare(strict_types=1);

namespace App\Auth\Command\ChangeEmail\Request;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepositoryInterface;
use App\Auth\Service\NewEmailConfirmTokenSenderInterface;
use App\Auth\Service\TokenGenerator;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private TokenGenerator $tokenGenerator;
    private NewEmailConfirmTokenSenderInterface $sender;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TokenGenerator $tokenGenerator,
        NewEmailConfirmTokenSenderInterface $sender
    ) {
        $this->userRepository = $userRepository;
        $this->tokenGenerator = $tokenGenerator;
        $this->sender = $sender;
    }

    public function userRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    public function tokenGenerator(): TokenGenerator
    {
        return $this->tokenGenerator;
    }

    public function sender(): NewEmailConfirmTokenSenderInterface
    {
        return $this->sender;
    }

    public function handle(Command $command): void
    {
        $user = $this->userRepository()->get(new Id($command->userId()));

        if (true === $this->userRepository()->hasByEmail($command->email())) {
            throw new \DomainException('Указанный E-mail уже существует.');
        }

        $date = new \DateTimeImmutable();

        $user->requestEmailChanging(
            $token = $this->tokenGenerator()->generate($date),
            $date,
            $command->email()
        );

        $this->sender()->send($command->email(), $token);
    }
}

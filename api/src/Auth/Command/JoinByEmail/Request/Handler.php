<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByEmail\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepositoryInterface;
use App\Auth\Service\JoinConfirmationSenderInterface;
use App\Auth\Service\PasswordHashGenerator;
use App\Auth\Service\TokenGenerator;

class Handler
{
    private UserRepositoryInterface $userRepository;
    private PasswordHashGenerator $hashGenerator;
    private TokenGenerator $tokenGenerator;
    private JoinConfirmationSenderInterface $sender;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordHashGenerator $hashGenerator,
        TokenGenerator $tokenGenerator,
        JoinConfirmationSenderInterface $sender
    ) {
        $this->userRepository = $userRepository;
        $this->hashGenerator = $hashGenerator;
        $this->tokenGenerator = $tokenGenerator;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        if (true === $this->userRepository->hasByEmail($command->email())) {
            throw new \DomainException('Пользователь уже существует с таким e-mail адресом');
        }

        $date = new \DateTimeImmutable();
        $token = $this->tokenGenerator->generate($date);

        $user = new User(
            Id::generate(),
            $date,
            $command->email(),
            $this->hashGenerator->hash($command->password()),
            $token
        );

        $this->userRepository->add($user);

        $this->sender->send($command->email(), $token);
    }
}

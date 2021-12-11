<?php

declare(strict_types=1);

namespace App\Auth\Command\JoinByNetwork;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\NetworkIdentity;
use App\Auth\Entity\User\User;
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
        $networkIdentity = new NetworkIdentity($command->identity(), $command->network());

        if (null !== $this->userRepository->findByNetwork($networkIdentity)) {
            throw new \DomainException('Пользователь уже существует.');
        }

        if (false !== $this->userRepository->hasByEmail($command->email())) {
            throw new \DomainException(
                sprintf('Пользователь c email %s уже существует.', $command->email()->value())
            );
        }

        $user = User::requestJoinByNetwork(
            Id::generate(),
            new \DateTimeImmutable(),
            $command->email(),
            $networkIdentity
        );

        $this->userRepository->add($user);
    }
}

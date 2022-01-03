<?php

declare(strict_types=1);

namespace App\Auth\Command\AttachNetwork;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\NetworkIdentity;
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
            throw new \DomainException('Социальная сеть уже привязана к акаунту пользователя.');
        }

        $user = $this->userRepository->get(new Id($command->userId()));

        $user->attachNetwork($networkIdentity);
    }
}

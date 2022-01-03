<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DomainException;

interface UserRepositoryInterface
{
    public function add(User $user): void;

    public function hasByEmail(Email $email): bool;

    public function findByConfirmToken(string $token): ?User;

    public function findByNetwork(NetworkIdentity $identity): ?User;

    /**
     * @param Id $id
     *
     * @return User
     * @throws DomainException
     */
    public function get(Id $id): User;
}

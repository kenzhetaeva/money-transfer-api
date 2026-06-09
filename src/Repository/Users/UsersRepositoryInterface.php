<?php

declare(strict_types=1);

namespace App\Repository\Users;

use App\Entity\User;

interface UsersRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function createUser(User $user);
}

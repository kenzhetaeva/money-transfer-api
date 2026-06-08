<?php

namespace App\Repository\Users;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UsersRepository implements UsersRepositoryInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}

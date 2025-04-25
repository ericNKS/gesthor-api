<?php

namespace App\Services\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserFind
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function byId(int $id): ?User {
        $user = $this->userRepository->find($id);

        if(!$user) {
            return null;
        }

        return $user;
    }

    public function byEmail(string $email): ?User {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if(!$user) {
            return null;
        }

        return $user;
    }
}
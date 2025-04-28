<?php

namespace App\Services\User;

use App\DTO\AuthRequest;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAuth
{
    public function __construct(
        private UserFind $userFind,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function execute(AuthRequest $authRequest): User
    {
        $user = $this->userFind->byEmail($authRequest->email);
        if (!$user) {
            throw new ConflictHttpException('User not found');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $authRequest->password)) {
            throw new UnauthorizedHttpException('', 'Invalid credentials');
        }

        return $user;
    }
}
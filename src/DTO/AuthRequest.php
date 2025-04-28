<?php

namespace App\DTO;

use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthRequest
{
    public string $email;
    public string $password;
    public function __construct(
        array $data
    ) {
        if(!isset($data["email"])){
            throw new BadRequestHttpException('Missing "email" parameter');
        }

        if(!isset($data["password"])){
            throw new BadRequestHttpException('Missing "password" parameter');
        }

        $this->email = $data["email"];

        $user = new User();
        $this->password = $data["password"];
    }
}
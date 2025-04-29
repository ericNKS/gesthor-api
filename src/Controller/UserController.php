<?php

namespace App\Controller;

use App\DTO\AuthRequest;
use App\Repository\UserRepository;
use App\Services\User\UserAuth;
use App\Services\User\UserCreate;
use App\Services\User\UserFind;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

#[Route('/api')]
final class UserController extends AbstractController
{
    #[Route('/user/{id}', name: 'find_user', methods: ['GET'])]
    public function find(
        int $id,
        UserRepository $repository,
    ): Response
    {
        $userFindService = new UserFind($repository);
        $user = $userFindService->byId($id);

        if(!$user) {
            return $this->json([
                'error' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/register', name: 'register_user', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        UserRepository $repository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): Response
    {
        $body = json_decode($request->getContent(), true);

        $findService = new UserFind($repository);

        $userCreateService = new UserCreate($em, $formFactory, $findService, $passwordHasher);
        $user = $userCreateService->execute($body);

        $userAuth = new AuthRequest([
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);

        $token = $jwtManager->create($userAuth);

        return $this->json([
            'user' => $user,
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    #[Route('/login', name: 'login_user', methods: ['POST'])]
    public function login(
        Request $request,
        FormFactoryInterface $formFactory,
        UserRepository $repository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): Response
    {
        $body = json_decode($request->getContent(), true);

        $authRequest = new AuthRequest($body);
        $userFind = new UserFind($repository);

        $userAuth = new UserAuth($userFind, $passwordHasher);

        $user = $userAuth->execute($authRequest);

        $token = $jwtManager->create($user);

        return $this->json([
            'token' => $token,
        ], Response::HTTP_CREATED);
    }

    #[Route('/logout', name: 'logout_app', methods: ['POST'])]
    public function logout(
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        TokenStorageInterface $tokenStorage
    ): Response
    {
        $token = $tokenStorage->getToken();

        $eventDispatcher->dispatch(new LogoutEvent($request, $token));

        return $this->json([], Response::HTTP_NO_CONTENT);
    }
}

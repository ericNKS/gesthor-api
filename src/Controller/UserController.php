<?php

namespace App\Controller;

use App\DTO\AuthRequest;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use App\Services\Company\CompanyFind;
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
    #[Route('/users/{id}', name: 'find_user', methods: ['GET'])]
    public function find(
        int $id,
        UserRepository $repository,
        TokenStorageInterface $tokenStorage
    ): Response
    {
        $atualUserCompanyId = $tokenStorage->getToken()->getUser()->getComId();

        $userFindService = new UserFind($repository);
        $user = $userFindService->byId($id);

        if(!$user) {
            return $this->json([
                'error' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }

        if ($atualUserCompanyId !== $user->getComId()) {
            return $this->json([
                'message' => 'Access denied',
            ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/users', name: 'get_user', methods: ['GET'])]
    public function show(
        TokenStorageInterface $tokenStorage
    ): Response
    {
        $token = $tokenStorage->getToken();
        $user = $token->getUser();

        return $this->json($user, Response::HTTP_OK);
    }

    #[Route('/register', name: 'register_user', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        UserRepository $repository,
        CompanyRepository $companyRepository,
        UserPasswordHasherInterface $passwordHasher,
        JWTTokenManagerInterface $jwtManager
    ): Response
    {
        $body = json_decode($request->getContent(), true);

        $findUserService = new UserFind($repository);
        $findCompanyService = new CompanyFind($companyRepository);

        $userCreateService = new UserCreate($em, $findCompanyService, $formFactory, $findUserService, $passwordHasher);
        $user = $userCreateService->execute($body);

        if (!$user) {
            return $this->json([
                'error' => 'something went wrong',
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $token = $jwtManager->create($user);

        return $this->json([
            'token' => $token
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

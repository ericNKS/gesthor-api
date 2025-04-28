<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Services\User\UserCreate;
use App\Services\User\UserFind;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/user')]
final class UserController extends AbstractController
{
    #[Route('/{id}', name: 'find_user', methods: ['GET'])]
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

    #[Route('', name: 'create_user', methods: ['POST'])]
    public function store(
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        UserRepository $repository,
    ): Response
    {
        $body = json_decode($request->getContent(), true);

        $findService = new UserFind($repository);

        $userCreateService = new UserCreate($em, $formFactory, $findService);
        $user = $userCreateService->execute($body);

        return $this->json($user, Response::HTTP_CREATED);
    }
}

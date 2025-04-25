<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/{id}', name: 'find_user', methods: ['GET'])]
    public function find(
        int $id
    ): Response
    {

        return $this->json(['id' => $id]);
    }

    #[Route('', name: 'create_user', methods: ['POST'])]
    public function store(
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response
    {
        $body = json_decode($request->getContent(), true);

        $user = new User();

        $form = $formFactory->create(UserType::class, $user);
        $form->submit($body);

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }
        if(!$form->isValid()) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['body' => $user]);
    }
}

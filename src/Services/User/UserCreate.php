<?php

namespace App\Services\User;

use App\Entity\Company;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserCreate
{
    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactory,
        private UserFind $userFind,
    ){}

    public function execute(array $userData): ?Company
    {
        $user = new User();

        $form = $this->formFactory->create(UserType::class, $user);
        $form->submit($userData);

        $this->isValid($form);

        $this->em->persist($user);
        $this->em->flush();
    }

    private function isValid(FormInterface $form): void {
        $data = $form->getData();

        $user = $this->userFind->byEmail($data->getEmail());
        if($user) {
            throw new BadRequestHttpException('User is already registered');
        }


        if ($form->isValid()) {
            return;
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        throw new BadRequestHttpException(json_encode($errors));
    }
}
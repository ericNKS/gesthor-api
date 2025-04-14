<?php

namespace App\Services\Company;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CompanyCreate
{
    public function __construct(
        private EntityManagerInterface $em,
        private FormFactoryInterface $formFactory
    ){}

    public function execute(array $companyData): Company {
        $company = new Company();

        $form = $this->formFactory->create(CompanyType::class, $company);
        $form->submit($companyData);

        $this->isValid($form);

        $this->em->persist($company);
        $this->em->flush();

        return $company;
    }

    private function  isValid (FormInterface $form) {
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
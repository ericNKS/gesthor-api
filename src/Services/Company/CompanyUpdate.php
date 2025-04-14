<?php

namespace App\Services\Company;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class CompanyUpdate
{
    public function __construct(
        private EntityManagerInterface $em
    ){
    }

    public function execute(
        Company $company,
        array $newDataCompany
    ): Company {

        if (isset($newDataCompany['name'])){
            $company->setName($newDataCompany['name']);
        }

        if (isset($newDataCompany['cnpj'])){
            throw new \Exception('cnpj nao pode ser alterado');
        }

        $this->em->flush();

        return $company;
    }
}
<?php

namespace App\Services\Company;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class Create
{
    public function __construct(
        private EntityManagerInterface $em
    ){}

    public function execute(array $companyData): Company {
        $this->isValid($companyData);

        $company = new Company();
        $company->setName($companyData['name']);
        $company->setCnpj($companyData['cnpj']);

        $this->em->persist($company);
        $this->em->flush();

        return $company;
    }

    private function  isValid (array $company) {
        if (!isset($company['cnpj']) ) {
            throw new \Exception('CNPJ is required');
        }

        if (strlen($company['cnpj']) != 14) {
            throw new \Exception('CNPJ length is invalid');
        }

        if(!isset($company['name'])) {
            throw new \Exception('Name is required');
        }

        if(strlen($company['name']) < 3) {
            throw new \Exception('Name length is invalid');
        }
    }
}
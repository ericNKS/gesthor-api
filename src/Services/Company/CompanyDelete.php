<?php

namespace App\Services\Company;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class CompanyDelete
{
    public function __construct(
        private EntityManagerInterface $em
    ){
    }

    public function softDelete(Company $company): void
    {
        $company->setDeletedAt(new \DateTimeImmutable());

        $this->em->flush();
    }

    public function delete(Company $company): void {
        $this->em->remove($company);
    }
}
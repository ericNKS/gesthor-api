<?php

namespace App\Services\Company;

use App\Entity\Company;
use App\Repository\CompanyRepository;

class CompanyFind
{
    public function __construct(
        private CompanyRepository $repository
    ) {}

    public function execute(int $id): ?Company {
        $company = $this->repository->find($id);

        if (!is_null($company->getDeletedAt())) {
            return null;
        }
        return $company;
    }
}
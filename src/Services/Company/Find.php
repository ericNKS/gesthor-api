<?php

namespace App\Services\Company;

use App\Entity\Company;
use App\Repository\CompanyRepository;

class Find
{
    public function __construct(
        private CompanyRepository $repository
    ) {}

    public function execute(int $id): ?Company {
        $company = $this->repository->find($id);
        return $company;
    }
}
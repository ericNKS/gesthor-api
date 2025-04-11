<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Services\Company\Find;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/company')]
final class CompanyController extends AbstractController
{
    #[Route('/{id}', name:'find_company', methods: ['GET'])]
    public function find(int $id, CompanyRepository $repository): Response
    {
        $findService = new Find($repository);

        $company = $findService->execute($id);

        if (!$company) {
            return $this->json([
                'error' => 'Company not found'
            ]);
        }

        return $this->json($company);
    }
    
}

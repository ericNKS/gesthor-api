<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Services\Company\CompanyCreate;
use App\Services\Company\CompanyDelete;
use App\Services\Company\CompanyFind;
use App\Services\Company\CompanyUpdate;
use Doctrine\DBAL\Types\StringType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/company')]
final class CompanyController extends AbstractController
{
    #[Route('/{id}', name:'find_company', methods: ['GET'], requirements:['id' => '\d+'])]
    public function find(int $id, CompanyRepository $repository): Response
    {
        $findService = new CompanyFind($repository);

        $company = $findService->execute($id);

        if (!$company) {
            return $this->json([
                'error' => 'Company not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($company->jsonSerialize());
    }

    #[Route('', name: 'create_company', methods: ['POST'])]
    public function store(
        Request $request,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory
    ): Response
    {
        $body = json_decode($request->getContent(), true);

        $companyService = new CompanyCreate($em, $formFactory);
        $company = $companyService->execute($body);

        return $this->json($company);
    }

    #[Route('/{id}', name:'update_company', methods: ['PATCH', 'PUT'], requirements:['id' => '\d+'])]
    public function update(
        int $id,
        CompanyRepository $repository,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $findService = new CompanyFind($repository);

        $company = $findService->execute($id);

        if(!$company) {
            return $this->json([
                'error' => 'Company not found'
            ], Response::HTTP_NOT_FOUND);
        }


        $body = json_decode($request->getContent(), true);

        $companyUpdate = new CompanyUpdate($em);

        $company = $companyUpdate->execute($company, $body);

        return $this->json($company->jsonSerialize());
    }

    #[Route('/{id}', name:'softDelete_company', methods: ['DELETE'], requirements:['id' => '\d+'])]
    public function delete(
        int $id,
        CompanyRepository $repository,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $findService = new CompanyFind($repository);

        $company = $findService->execute($id);

        if(!$company) {
            return $this->json([
                'error' => 'Company not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $body = json_decode($request->getContent(), true);

        $companyUpdate = new CompanyDelete($em);

        $company = $companyUpdate->softDelete($company);

    return $this->json([], Response::HTTP_NO_CONTENT);
    }
}

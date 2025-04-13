<?php

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Services\Company\Create;
use App\Services\Company\Find;
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

        $companyService = new Create($em, $formFactory);
        $company = $companyService->execute($body);

        return $this->json($company);
    }
}

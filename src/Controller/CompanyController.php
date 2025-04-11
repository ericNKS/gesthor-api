<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/company')]
final class CompanyController extends AbstractController
{
    #[Route('/{id}', name:'find_company', methods: ['GET'])]
    public function find(): Response
    {
        
        return $this->json([]);
    }
    
}

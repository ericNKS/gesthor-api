<?php

namespace App\Tests\Services\Company;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Services\Company\Create;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateCompanyTest extends TestCase
{
    public function testCreate(): void
    {
        $companyData = [
            'name' => 'Acme Inc.',
            'cnpj' => '12345678910121',
        ];

        // Create an actual service to test
        $createService = new Create(
            $this->getEntityManagerMock($companyData),
            $this->getFormFactoryMock($companyData)
        );

        $result = $createService->execute($companyData);

        $this->assertInstanceOf(Company::class, $result);
        $this->assertEquals($companyData['name'], $result->getName());
        $this->assertEquals($companyData['cnpj'], $result->getCnpj());
    }

    private function getFormFactoryMock(array $companyData)
    {
        // Prepare a stub form that will update the Company
        $form = $this->createMock(FormInterface::class);

        // Make submit() update the Company and return the form
        $form->expects($this->once())
            ->method('submit')
            ->willReturnCallback(function($data) use ($form, $companyData) {
                // Get the real Company object from the form's data
                $company = $form->getData();
                // Update it with our test data
                $company->setName($companyData['name']);
                $company->setCnpj($companyData['cnpj']);
                return $form;
            });

        // Make isValid() return true to prevent exception
        $form->expects($this->once())
            ->method('isValid')
            ->willReturn(true);

        // Store the Company object so we can modify it
        $capturedCompany = null;

        // Make getData() return our captured Company
        $form->expects($this->any())
            ->method('getData')
            ->willReturnCallback(function() use (&$capturedCompany) {
                return $capturedCompany;
            });

        // Create the form factory mock
        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->expects($this->once())
            ->method('create')
            ->willReturnCallback(function($type, $company) use ($form, &$capturedCompany) {
                // Store the company object passed to create()
                $capturedCompany = $company;
                return $form;
            });

        return $formFactory;
    }

    private function getEntityManagerMock(array $companyData)
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($company) use ($companyData) {
                return $company instanceof Company
                    && $company->getName() === $companyData['name']
                    && $company->getCnpj() === $companyData['cnpj'];
            }));

        $entityManager->expects($this->once())
            ->method('flush');

        return $entityManager;
    }
}

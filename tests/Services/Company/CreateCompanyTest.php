<?php

namespace App\Tests\Services\Company;

use App\Entity\Company;
use App\Services\Company\Create;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CreateCompanyTest extends TestCase
{
    public function testCreate(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($company) {
                return $company instanceof Company
                    && $company->getName() === 'Acme Inc.'
                    && $company->getCnpj() === '12345678910121';
            }));

        $entityManager->expects($this->once())
            ->method('flush');

        $createService = new Create($entityManager);

        $company = [
            'name' => 'Acme Inc.',
            'cnpj' => '12345678910121',
        ];

        $result = $createService->execute($company);

        $this->assertInstanceOf(Company::class, $result);
        $this->assertEquals($company['name'], $result->getName());
        $this->assertEquals($company['cnpj'], $result->getCnpj());
    }
}

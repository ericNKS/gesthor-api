<?php

namespace App\Tests\Services\Company;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Services\Company\CompanyFind;
use PHPUnit\Framework\TestCase;

class FindCompanyTest extends TestCase
{
    public function testExecute(): void
    {
        $mockCompany = new Company();
        $mockCompany->setName('Test Company');
        
        $mockRepository = $this->createMock(CompanyRepository::class);
        
        // Configure the mock to return our company when find(1) is called
        $mockRepository->method('find')
            ->willReturn($mockCompany);
        
        // CompanyCreate the service with our mock repository
        $findService = new CompanyFind($mockRepository);

        // Execute the service
        $result = $findService->execute(1);

        // Assert the result is our mock company
        $this->assertSame($mockCompany, $result);
    }
    
    public function testExecuteNotFound(): void
    {
        // CompanyCreate a mock repository
        $mockRepository = $this->createMock(CompanyRepository::class);
        
        // Configure the mock to return null when find(999) is called
        $mockRepository->method('find')
            ->willReturnMap([
                [999, null]  // When find(999) is called, return null
            ]);
        
        // CompanyCreate the service with our mock repository
        $findService = new CompanyFind($mockRepository);
        
        // Execute the service
        $result = $findService->execute(999);
        
        // Assert the result is null
        $this->assertNull($result);
    
    }
}

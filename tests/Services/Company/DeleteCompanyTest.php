<?php

namespace App\Tests\Services\Company;

use App\Entity\Company;
use App\Services\Company\CompanyDelete;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class DeleteCompanyTest extends TestCase
{
    private EntityManagerInterface $entityManagerMock;
    private CompanyDelete $companyDelete;
    private Company $company;

    protected function setUp(): void
    {
        // Create mock objects
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $this->companyDelete = new CompanyDelete($this->entityManagerMock);

        // Create a company for testing
        $this->company = $this->createMock(Company::class);
    }
    public function testSoftDeleteSetsDeletedAtAndFlushes(): void
    {
        // Arrange
        // Expect the setDeletedAt method to be called once with any DateTimeImmutable object
        $this->company->expects($this->once())
            ->method('setDeletedAt')
            ->with($this->callback(function($arg) {
                return $arg instanceof \DateTimeImmutable;
            }));

        // Expect flush to be called once
        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        // Act
        $this->companyDelete->softDelete($this->company);
    }

    public function testDeleteRemovesCompanyFromEntityManager(): void
    {
        // Arrange
        // Expect remove to be called once with the company object
        $this->entityManagerMock->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($this->company));

        // Act
        $this->companyDelete->delete($this->company);
    }
}

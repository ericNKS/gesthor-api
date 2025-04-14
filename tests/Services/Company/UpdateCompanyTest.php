<?php

namespace App\Tests\Services\Company;

use App\Entity\Company;
use App\Services\Company\CompanyUpdate;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class UpdateCompanyTest extends TestCase
{
    public function testUpdate(): void
    {
        $emMock = $this->createMock(EntityManagerInterface::class);
        $companyUpdate = new CompanyUpdate($emMock);

        $company = $this->createMock(Company::class);

        $newDataCompany = [
            'name' => 'New Company name'
        ];

        $company->expects($this->once())
            ->method('setName')
            ->with($this->equalTo($newDataCompany['name']));

        $emMock->expects($this->once())
            ->method('flush');

        $updatedCompany = $companyUpdate->execute($company, $newDataCompany);

        $this->assertSame($company, $updatedCompany);
    }

    public function testUpdateWithoutName(): void {
        $emMock = $this->createMock(EntityManagerInterface::class);
        $companyUpdate = new CompanyUpdate($emMock);

        $company = $this->createMock(Company::class);

        $newDataCompany = [
            'name' => ''
        ];

        $company->expects($this->never())
            ->method('setName');

        $emMock->expects($this->once())
            ->method('flush');

        $updatedCompany = $companyUpdate->execute($company, $newDataCompany);

        $this->assertSame($company, $updatedCompany);
    }
}

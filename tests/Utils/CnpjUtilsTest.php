<?php

namespace App\Tests\Utils;

use App\Utils\CnpjUtils;
use PHPUnit\Framework\TestCase;

class CnpjUtilsTest extends TestCase
{
    public function testCnpjInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $cnpj = new CnpjUtils("0000000000");
    }

    public function testFormatedCnpj(): void
    {
        $cnpjValue = "00000000000000";
        $cnpjExpect = "00.000.000/0000-00";

        $cnpj = new CnpjUtils($cnpjValue);
        $formatedCnpj = $cnpj->toStringFormated();

        $this->assertEquals(
            $cnpjExpect,
            $formatedCnpj
        );
    }

    public function testCnpjValid(): void {
        $cnpjValue = new CnpjUtils("00.416.968/0001-01");

        $this->assertTrue(
            $cnpjValue->isValid(),
            'Cnpj Invalid'
        );

    }
}

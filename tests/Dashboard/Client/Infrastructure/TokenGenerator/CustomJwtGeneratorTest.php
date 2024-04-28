<?php

namespace App\Tests\Dashboard\Client\Infrastructure\TokenGenerator;

use App\Dashboard\Client\Infrastructure\TokenGenerator\CustomJwtGenerator;
use App\Tests\Dashboard\Client\Domain\RegisterClientMother;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CustomJwtGeneratorTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @test
     */
    public function generateTokenTest()
    {
        $jwtProvider = $this->getContainer()->get(CustomJwtGenerator::class);

        $token = $jwtProvider->generateToken(RegisterClientMother::register());

        $this->assertIsString($token);
    }
}

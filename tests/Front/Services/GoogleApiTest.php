<?php

namespace App\Tests\Front\Services;

use App\Services\GoogleApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GoogleApiTest extends KernelTestCase
{
    public function testGoogleApi(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $googleApi = static::getContainer()->get(GoogleApi::class);

        $response = $googleApi->fetch("Paris", "France");

        $this->assertNotEmpty($response);
        $this->assertStringContainsString("Paris", $response);
        $this->assertStringContainsString("France", $response);
    }
}

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

        $infosGoogle = $googleApi->fetch("Paris", "France");
        $urlExpected = "https://www.google.com/maps/embed/v1/place?q=Paris%2BFrance&key=AIzaSyBkrzO3Tl1cssskHuzaVf1u69AymUt_Ih0";

        $this->assertEquals($urlExpected, $infosGoogle);

    }
}

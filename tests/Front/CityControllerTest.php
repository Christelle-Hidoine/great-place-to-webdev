<?php

namespace App\Tests\Front;

use Symfony\Component\Panther\PantherTestCase;

class CityControllerTest extends PantherTestCase
{

    public function testCityDetailPage(): void
    {
        $client = static::createPantherClient();

        $client->request('GET', '/cities/514'); 
        // Take a screenshot and save it for inspection
        $client->takeScreenshot('screenshot.png');

        $this->assertStringContainsString('icon-open', $client->getPageSource());
        
    }
    
}

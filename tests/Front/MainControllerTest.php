<?php

namespace App\Tests\Front;

use Symfony\Component\Panther\PantherTestCase;

class MainControllerTest extends PantherTestCase
{
    public function testMainHomepage(): void
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertStringContainsString('Envie d\'évasion', $client->getPageSource());
        $this->assertStringContainsString('amcharts-main-div', $client->getPageSource());
    }
}

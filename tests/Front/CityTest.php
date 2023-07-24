<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CityTest extends WebTestCase
{
    public function testCityShow(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/cities/401');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Georges-sur-Ledoux');
    }
}

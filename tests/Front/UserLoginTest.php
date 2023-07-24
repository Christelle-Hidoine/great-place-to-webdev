<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserLoginTest extends WebTestCase
{
    private $validCsrfToken;

    public function testLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->validCsrfToken = $crawler->filter('input[name="_csrf_token"]')->attr('value');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Connexion');
    }

    public function testLoginWithValidCredentials(): void
    {
        $client = static::createClient();
        $client->request('POST', '/login', [
            'email' => 'user1@user1.com',
            'password' => '$2y$13$vdXTZhmBoGkfamUa5gC7iOCQ1lTDgyPbI6B1bXjL2C7QVzfGrwADu',
            '_csrf_token' =>  $this->validCsrfToken,
        ]);

        $this->assertResponseRedirects();
    }

}

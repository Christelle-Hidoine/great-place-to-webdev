<?php

namespace App\Tests\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRegistrationTest extends WebTestCase
{
    public function testAddUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sign-in');
        $this->assertResponseIsSuccessful();
 
        $buttonCrawlerNode = $crawler->selectButton('Sauvegarder');

        $form = $buttonCrawlerNode->form();

        $form['user[firstname]'] = 'Duzanna';
        $form['user[lastname]'] = 'Nouveau';
        $form['user[username]'] = 'suze';
        $form['user[email]'] = 'duzanna@suzanna.com';
        $form['user[password]'] = "Nouveau@2";

        $client->submit($form);
        
        $this->assertResponseRedirects();
    }
}
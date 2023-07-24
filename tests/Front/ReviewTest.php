<?php

namespace App\Tests\Front;

use App\Form\Front\ReviewType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewTest extends WebTestCase
{
    public function testAddReviewWithoutSecurity(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reviews/401/review/add');
        $this->assertResponseIsSuccessful();

        $buttonCrawlerNode = $crawler->selectButton('Sauvegarder');

        $form = $buttonCrawlerNode->form();

        $form['review[username]'] = 'Suzanna';
        $form['review[content]'] = "ce soir chez Suzanna, c'est soirée Disco";
        $form['review[rating]'] = 4;

        $client->submit($form);

        $this->assertResponseRedirects();

    }
}

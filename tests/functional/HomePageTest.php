<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $button = $crawler->selectLink('Se connecter');
        $this->assertEquals(1, count($button));


        $this->assertSelectorTextContains('h5', 'Ajouter un annonce');
    }
}

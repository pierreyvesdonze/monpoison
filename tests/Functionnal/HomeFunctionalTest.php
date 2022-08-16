<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * php bin/phpunit tests/Functionnal/HomeFunctionalTest.php
 */
class HomeFunctionalTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertPageTitleContains('Monpoison');
        $this->assertCount(1, $crawler->filter('h2'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', "Obtenez des statistiques détaillées sur vos habitudes et maîtrisez vos consommation d'alcool au quotidien.");

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}

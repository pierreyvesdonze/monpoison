<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * php bin/phpunit tests/HomeFunctionalTest.php
 */
class HomeFunctionalTest extends WebTestCase
{
    public function testHomePage()
        {
            $client = static::createClient();
            $crawler = $client->request('GET', '/');

            $this->assertResponseIsSuccessful();
            $this->assertSelectorTextContains('div', 'Bienvenue sur mon poison');
        }
}
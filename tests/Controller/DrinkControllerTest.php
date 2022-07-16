<?php

namespace App\Controller\Tests;

use App\Entity\Alcool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * php bin/phpunit tests/Controller/DrinkControllerTest.php
 */
class DrinkControllerTest extends WebTestCase
{
    // /** @test */
    // public function getCalendarTest()
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/consommations/voir');

    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('custom-btn', 'Ajouter une consommation');
    //     $this->assertSelectorTextContains('custom-btn', 'Ajouter une sobriété');
    // }

    /** @test */
    public function addDrinkTest()
    {
        $client = static::createClient();
        $client->request('POST', '/consommation/ajouter');

        $client->submitForm('submit', [
            'form[alcool]' => new Alcool,
            'form[quantity]' => '1',
            'form[date]' => new \DateTime(),
            'form[cost]' => '1'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect(true);
    }
}

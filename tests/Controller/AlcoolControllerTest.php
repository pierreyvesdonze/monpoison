<?php

namespace App\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * php bin/phpunit tests/Controller/AlcoolControllerTest.php
 */
class AlcoolControllerTest extends WebTestCase
{
    // public function testCalendarPage()
    // {
    //     $client = static::createClient();
    //     $client->request('GET', '/consommations/voir');
    //     $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    // }

    public function testAuthPageIsRestricted() {
        $client = static::createClient();
        $client->request('GET', '/consommations/voir');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    // public function testScoreForm()
    // {
    //     $client = static::createClient();
    //     $client->request('POST', '/alcool/test/addiction');

    //     $client->submitForm('submit', [
    //         'form[frequencyComsumption][choices]' => '1',
    //         'form[drinkByDay]' => '1',
    //         'form[fiveDrinksFrequency]' => '1',
    //         'form[stopControl]' => '1',
    //         'form[failAttempt]' => '1',
    //         'form[needFirstDrink]' => '1',
    //         'form[regrets]' => '1',
    //         'form[noMemory]' => '1',
    //     ]);

    //     $this->assertResponseRedirects();
    //     $client->followRedirect(true);
    // }
}

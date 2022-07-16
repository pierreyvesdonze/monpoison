<?php

namespace App\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * php bin/phpunit tests/Controller/HomeControllerTest.php
 */
class HomeControllerTest extends WebTestCase
{
    public function testContactForm()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/contact');

        $client->submitForm('Submit', [
            'form[email]' => 'test@test.test',
            'form[subject]' => 'Sujet du test',
            'form[text]' => 'Contenu du mail',
            'form[captcha]' => 'Site key: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
    }
}

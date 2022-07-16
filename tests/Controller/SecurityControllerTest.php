<?php

namespace App\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * php bin/phpunit tests/Controller/SecurityControllerTest.php
 */
class SecurityControllerTest extends WebTestCase
{
    public function testDisplayLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        // $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('connexion')->form([
            '_username' => 'patrick@dills.fr',
            '_password' => 'fakepassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
    }

    public function testSuccessLogin()
    {
        $client = static::createClient();
        $csrfToken = $client->getContainer()->get('security.csrf.token_manager')->getToken('authenticate');
        $client->request('POST', '/login', [
            '_csrf_token' => $csrfToken,
            '_username' => 'test@test.test',
            '_password' => '$2y$13$KqAmUS2v6HKt8F8jw7ilk.3zsywO/wG7ppluW3WKOjlW0PH26suFC'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
    }
}
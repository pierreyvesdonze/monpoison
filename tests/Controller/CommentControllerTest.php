<?php

namespace App\Controller\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * php bin/phpunit tests/Controller/CommentControllerTest.php
 */
class CommentControllerTest extends WebTestCase
{
    /** @test */
    public function setComment()
    {
        $client = static::createClient();
        $client->request('POST', '/ajouter/{120}');

        $crawler = $client->followRedirect();

        // $client->submitForm('submit', [
        //     'form[content]'=>'Ceci est un commentaire'
        // ]);

        $form = $crawler->selectButton("commenter")->form();

        $form['content'] = "contenu du commentaire";

        $crawler = $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirects();
    }

    /** @test */
    public function editComment()
    {
        $client = static::createClient();
        $client->request('POST', '/{120}/editer');

        $client->submitForm('submit', [
            'form[content]' => 'Ceci est un commentaire modifié'
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
    }
}

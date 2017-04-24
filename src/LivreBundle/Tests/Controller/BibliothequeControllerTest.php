<?php

namespace LivreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BibliothequeControllerTest extends WebTestCase
{
    public function testListe()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/liste');
    }

    public function testAjout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajout');
    }

    public function testSupprime()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprime');
    }

}

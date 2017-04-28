<?php

namespace LivreBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LieuControllerControllerTest extends WebTestCase
{
    public function testListe()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/liste');
    }

    public function testAjoute()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ajoute');
    }

    public function testModifie()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/modifie');
    }

    public function testSupprime()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/supprime');
    }

}

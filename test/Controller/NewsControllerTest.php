<?php
namespace test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class NewsControllerTest extends WebTestCase
{
    /**
     * Test concernant les news
     */
    public function testPresenceHomepage()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $client->followRedirects();

        // Pas d'erreur
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
        // Présence d'une news
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Dernières actualités")')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Run 4")')->count()
        );
    }


}
<?php
namespace test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class DeviseControllerTest extends WebTestCase
{
    public function testAccesPage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $client->followRedirects();

        // Pas d'erreur
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
        // Element d'ergonomie
        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Devises")')->count()
        );
        // Présence d'un menu déroulant
        $this->assertEquals(
            0,
            $crawler->filter('html:contains("select")')->count()
        );
        // Lien bouton admin
        $this->assertEquals(
            0,
            $crawler->selectLink('Admin')->count()
        );
        $this->assertEquals(
            1,
            $crawler->selectLink('Bug')->count()
        );
        $this->assertEquals(
            1,
            $crawler->selectLink('Github')->count()
        );
        // Tenative d'accès à des pages interdites => redirection vers la page de login
        $crawler = $client->request('GET', '/admin');
        $this->assertGreaterThan(
            0,
            strpos($crawler->getBaseHref(), "login")
        );

    }
}
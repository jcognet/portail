<?php
namespace test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class AdminControllerTest extends WebTestCase
{
    /**
     * Test permettant la connexion à la partie admin
     */
    public function testConnexion()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $client->followRedirects();

        $form              = $crawler->selectButton('Se connecter')->form();
        $form["_username"] = "jcognet@gmail.com";
        $form["_password"] = "jcognet@gmail.com";
        $crawler           = $client->submit($form);

        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode(),
            "La page après connexion est inaccessible."
        );

        $this->assertEquals(
            1,
            $crawler->selectLink('Admin')->count()
        );
        $crawler = $client->click($crawler->selectLink('Admin')->link());

        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode(),
            "La page admin est inaccessible."
        );

        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Nombre d\'utilisateurs actifs :")')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Date de début")')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('html:contains("Date de fin")')->count()
        );

    }


    /**
     * Test concernant la homepage admin
     */
    public function testHomepageConnexion()
    {

    }
}
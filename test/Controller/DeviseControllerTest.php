<?php
namespace test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class DeviseControllerTest extends WebTestCase
{
    /**
     * Test concernant l'accès aux pages
     */
    public function testAccesPage()
    {
        $client  = static::createClient();
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
        // Mode de blabla sur le mode de test
        $this->assertEquals(
            0,
            $crawler->filter('html:contains(" Mode : Serveur de test")')->count()
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
        // Tentative d'accès à des pages interdites => redirection vers la page de login
        $listePageAdmin = array('/admin', '/profile/edit', '/profile', '/user/profile/config/alert');
        foreach ($listePageAdmin as $page) {
            $this->assertEquals(
                200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
                $client->getResponse()->getStatusCode(),
                "La page " . $page . " est accessible."
            );
            // Vérification : est-ce que la page est protégée ?
            $crawler = $client->request('GET', $page);
            $this->assertGreaterThan(
                0,
                strpos($crawler->getBaseHref(), "login")
            );
        }

        // Tentative de connexion aux pages du site
        $listePage = array('/commun/contact', '/commun/devise/json/1',
                           '/commun/devise/affiche/1', '/login', '/logout', '/register',
                           '/register/check-email', '/resetting/request', '/resetting/check-email'
        );
        foreach ($listePage as $page) {
            $crawler = $client->request('GET', $page);
            $this->assertEquals(
                200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
                $client->getResponse()->getStatusCode(),
                "La page " . $page . " est accessible."
            );
        }
    }

    /**
     * Test sur les devises en valeur
     */
    public function testDevise()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/');
        $client->followRedirects();

        $this->assertEquals(
            1,
            $crawler->filter('#sltDevise')->count()
        );

        $crawler = $client->request('GET', '/commun/devise/json/1');
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            "Le retour n'est pas du JSON."
        );
        // Champ présent 1 fois
        $champsPresent = array('label', 'symbole', 'cours', 'moyenne_30', 'moyenne_60', 'moyenne_90', 'moyenne_120');
        $crawler       = $client->getCrawler();
        foreach ($champsPresent as $champ) {
            $this->assertTrue(substr_count($client->getResponse()->getContent(), $champ) == 1, $champ . " n'est oas présent.");
        }
        // Champ présent 30 fois
        $champsPresent30 = array('date', 'taux');
        foreach ($champsPresent30 as $champ) {
            $this->assertTrue(substr_count($client->getResponse()->getContent(), $champ) == 22, $champ . " n'est oas présent 30 fois.");
        }
    }

    /**
     * Gestion de l'affichage
     */
    public function testAfficheDevise()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/commun/devise/affiche/1');
        $client->followRedirects();
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * Calcule les taux
     */
    public function testCalculeTaux()
    {
        $client  = static::createClient();
        $crawler = $client->request('GET', '/commun/devise/calcul/1/1000/0');
        $client->followRedirects();
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $client->getResponse()->getContent() == '"121 200"',
            'Mauvaise valeur pour convertir en yen (attendue : ' . $client->getResponse()->getContent() . ', obtenue : ' . $client->getResponse()->getContent() . ')'
        );

        $crawler = $client->request('GET', '/commun/devise/calcul/1/0/121200');
        $client->followRedirects();
        $this->assertEquals(
            200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $client->getResponse()->getStatusCode()
        );
        $this->assertEquals(
            '"1 000"',
            $client->getResponse()->getContent(),
            'Mauvaise valeur pour convertir en euro (attendue : ' . $client->getResponse()->getContent() . ', obtenue : ' . $client->getResponse()->getContent() . ')'
        );
    }
}
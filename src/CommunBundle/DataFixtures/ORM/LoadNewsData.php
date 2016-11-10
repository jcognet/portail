<?php

namespace CommunBundle\DataFixtures\ORM;

use CommunBundle\Entity\Devise;
use CommunBundle\Entity\News;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadNewsData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $newsVersion1 = new News();
        $newsVersion1->setDateCreation(new \DateTime('2016-10-29'))
            ->setDateMiseEnLigne(new \DateTime('2016-11-03'))
            ->setTitre('Run 1 : Configuration technique')
            ->setCorps('
            <ul>
            <li>Création compte github & configuration</li>
<li>Mise en place de la page Wiki</li>
<li>Mise en place des runs</li>
<li>Installation de PHPstorm</li>
<li>Installation de Symfony</li>
<li>Installation de Bootstrap</li>
<li>Installation de vendors tels que FosUserBundle et FosJsRouting</li>
<li>Mise en place de l\'architecture du projet</li>
</ul>
<br/>
Début : Samedi 29 Octobre<br/>
<br/>
Fin estimé : Jeudi 03 Novembre<br/>
            ');
        $manager->persist($newsVersion1);

        $newsVersion2 = new News();
        $newsVersion2->setDateCreation(new \DateTime('2016-11-04'))
            ->setDateMiseEnLigne(new \DateTime('2016-11-08'))
            ->setTitre('Run 2 : Création des entités')
            ->setCorps('
            <ul>
            <li>Mise en place du modèle objet</li>
<li>Mise en place des fixtures</li>
<li>Système de news</li>
<li>Alimentation par le webservice & enregistrement de la donnée de référence</li>
</ul>
<br/>
Début : Vendredi 04 Novembre<br/>
<br/>
Fin estimé : Dimanche 27 Novembre<br/>
<br/>
Fin réel : Mardi 8 Novembre<br/>
            ');
        $manager->persist($newsVersion2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}

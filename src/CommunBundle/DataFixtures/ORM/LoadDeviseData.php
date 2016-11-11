<?php

namespace CommunBundle\DataFixtures\ORM;

use CommunBundle\Entity\Devise;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDeviseData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $deviseYen = new Devise();
        $deviseYen->setCode('JPY')
            ->setCodeWebservice('JPY')
            ->setLabel('Yen')
            ->setMoyenne30Jours(0)
            ->setMoyenne60Jours(0)
            ->setMoyenne90Jours(0)
            ->setSymbole('¥')
            ->setMoyenne120Jours(0);
        $manager->persist($deviseYen);

        $deviseDollars = new Devise();
        $deviseDollars->setCode('USD')
            ->setCodeWebservice('USD')
            ->setLabel('Dollars')
            ->setMoyenne30Jours(0)
            ->setMoyenne60Jours(0)
            ->setMoyenne90Jours(0)
            ->setSymbole('$')
            ->setMoyenne120Jours(0);
        $manager->persist($deviseDollars);

        $deviseNok = new Devise();
        $deviseNok->setCode('NOK')
            ->setCodeWebservice('NOK')
            ->setLabel('Couronne Norvégienne')
            ->setMoyenne30Jours(0)
            ->setMoyenne60Jours(0)
            ->setMoyenne90Jours(0)
            ->setMoyenne120Jours(0);
        $manager->persist($deviseNok);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}

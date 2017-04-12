<?php

namespace CommunBundle\DataFixtures\ORM;

use DeviseBundle\Entity\Devise;
use DeviseBundle\Entity\SuiviDevise;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('jcognet@gmail.com')
            ->setUsername('jcognet@gmail.com')
            ->setUsernameCanonical('jcognet')
            ->setEnabled(true)
            ->setPlainPassword('jcognet@gmail.com')
            ->setPassword('jcognet@gmail.com')
            ->setSuperAdmin(true);

        $suiviDevise = new SuiviDevise();
        $suiviDevise->setSeuilMax(120)
            ->setSeuilMin(110)
            ->setUser($user)
            ->setDevise($manager->getReference(Devise::class, 1));
        $user->addListeDevise($suiviDevise);

        $manager->persist($suiviDevise);
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}

<?php

namespace CommunBundle\DataFixtures\ORM;

use CommunBundle\Entity\Devise;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('jcognet@gmail.com')
        ->setUsername('jcognet')
            ->setUsernameCanonical('jcognet')
            ->setEnabled(true)
            ->setPlainPassword('jcognet')
            ->setPassword('jcognet')
            ->setSuperAdmin(true)
        ;

        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}

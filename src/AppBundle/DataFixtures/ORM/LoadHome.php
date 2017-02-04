<?php

// src/AppBundle/DataFixtures/ORM/LoadHome.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Home;

class LoadHome implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $homes = ['here', 'new', 'popular', 'random'];

        foreach($homes as $h) {
            $home = new Home();
            $home->setName($h);
            $manager->persist($home);
        }
        $manager->flush();
    }
}
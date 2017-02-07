<?php

// src/AppBundle/DataFixtures/ORM/LoadHome.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Homes;

class LoadHome implements FixtureInterface
{
    public function load(ObjectManager $manager) {
        
        $em = $this->getDoctrine()->getManager();
        $homesList = ['here', 'new', 'popular', 'random'];

        foreach($homesList as $h) {
            $home = new Homes();
            $home->setName($h);
            $em->persist($home);
        }
        $em->flush();
    }
}
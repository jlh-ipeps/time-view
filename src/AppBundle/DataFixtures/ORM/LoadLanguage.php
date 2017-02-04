<?php

// src/AppBundle/DataFixtures/ORM/LoadLanguage.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Languages;

class LoadLanguage implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $LanguageList = [
            ['iso' => 'de', 'name', 'Deutch'],
            ['iso' => 'en', 'name', 'English'],
            ['iso' => 'fr', 'name', 'Français'],
            ['iso' => 'nl', 'name', 'Nederlands'],
        ];

        foreach($LanguageList as $l) {
            $language = new Languages();
            $language->setIso($l.iso);
            $language->setName($l.name);
            $manager->persist($language);
        }
        $manager->flush();
    }
}
<?php

namespace App\DataFixtures;

use App\Entity\Compagnies;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $compagnies = new Compagnies();
        $compagnies->setName("Cfsma");
        $manager->persist($compagnies);
        $manager->flush();
}
    }
    

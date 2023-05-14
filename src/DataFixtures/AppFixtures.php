<?php

namespace App\DataFixtures;

use App\Entity\Gateau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();
        for ($i=0; $i<100; $i++) {
            $gateau = new Gateau();
            $gateau->setNom($faker->name());
            $gateau->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2));
            $manager->persist($gateau);
        }
        $manager->flush();
    }
}

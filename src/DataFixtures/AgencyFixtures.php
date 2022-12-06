<?php

namespace App\DataFixtures;

use App\Entity\Agency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AgencyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($agencies = 1; $agencies <= 10; $agencies++){
            $agency = new Agency();
            $agency->setName($faker->company());

            $manager->persist($agency);
            // Enregistre dans une référence
            $this->addReference('agency_'. $agencies, $agency);
        }
        

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Adress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AdressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($adresses = 1; $adresses <= 50; $adresses++){
            
            $adress = new Adress();

            $adress->setNumber($faker->numberBetween(100, 1050));
            $adress->setStreet($faker->streetAddress());
            $adress->setPostalCode($faker->postcode());
            $adress->setCity($faker->city());

            $manager->persist($adress);

            // Enregistre dans une référence
            $this->addReference('adress_'. $adresses, $adress);
        }
        

        $manager->flush();
    }
}

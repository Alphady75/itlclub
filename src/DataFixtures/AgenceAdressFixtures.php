<?php

namespace App\DataFixtures;

use App\Entity\AgenceAdress;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AgenceAdressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($adresses = 1; $adresses <= 50; $adresses++){
            $agency = $this->getReference('agency_'. $faker->numberBetween(1, 10));

            $adress = new AgenceAdress();

            $adress->setNumber($faker->numberBetween(100, 1050));
            $adress->setStreet($faker->streetAddress());
            $adress->setPostalCode($faker->postcode());
            $adress->setCity($faker->city());
            $adress->setAgence($agency);

            $manager->persist($adress);

            // Enregistre dans une référence
            $this->addReference('agenceadress_'. $adresses, $adress);
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AgencyFixtures::class,
        ];
    }
}

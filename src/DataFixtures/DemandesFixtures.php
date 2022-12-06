<?php

namespace App\DataFixtures;

use App\Entity\Demande;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class DemandesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($demandes = 1; $demandes <= 50; $demandes++){
            $user = $this->getReference('user_'. $faker->numberBetween(1, 50));

            $demande = new Demande();

            $demande->setUser($user);
            $demande->setHidenprofil($faker->numberBetween(0, 1));
            $demande->setDownloaddata($faker->numberBetween(0, 1));
            $demande->setDeletedata($faker->numberBetween(0, 1));
            $demande->setDeleteCompte($faker->numberBetween(0, 1));
            $demande->setStatut(0);

            $manager->persist($demande);
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
        ];
    }
}

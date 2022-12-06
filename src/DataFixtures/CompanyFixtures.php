<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /*$faker = Faker\Factory::create('fr_FR');

        for($companies = 1; $companies <= 50; $companies++){
            $user = $this->getReference('user_'. $faker->unique(true)->numberBetween(1, 50));
            $adress = $this->getReference('adress_'. $faker->unique(true)->numberBetween(1, 50));

            $company = new Company();

            $company->setUser($user);
            $company->setAdress($adress);
            $company->setName($faker->company());
            $company->setSiret($faker->numberBetween(10, 290));
            $company->setNbEmployees($faker->numberBetween(1, 10));
            $company->setPhoneNumber($faker->phoneNumber());
            $company->setEmail($faker->companyEmail());

            //$faker->streetAddress() $faker->city() $faker->hexColor() $faker->name() . ' ' . $faker->firstName() $faker->phoneNumber() $faker->country()

            $manager->persist($company);
            // Enregistre dans une référence
            $this->addReference('company_'. $companies, $company);
        }
        

        //$manager->flush();*/
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            AdressFixtures::class,
        ];
    }
}

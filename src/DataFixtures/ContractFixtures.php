<?php

namespace App\DataFixtures;

use App\Entity\Contract;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ContractFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
       /* $faker = Faker\Factory::create('fr_FR');

        for($contrats = 1; $contrats <= 50; $contrats++){
            $company = $this->getReference('company_'. $faker->unique(true)->numberBetween(1, 50));

            $contrat = new Contract();

            $contrat->setCompany($company);
            $contrat->setCommercial($faker->firstName());
            $contrat->setContractState($faker->numberBetween(0, 1));
            $contrat->setAuthorizedPerson1($faker->firstName());
            $contrat->setAuthorizedPerson2($faker->firstName());
            $contrat->setAuthorizedPerson3($faker->firstName());

            $manager->persist($contrat);
            // Enregistre dans une référence
            $this->addReference('contrat_'. $contrats, $contrat);
        }
        

        $manager->flush();*/
    }

    public function getDependencies()
    {
        return [
            CompanyFixtures::class,
        ];
    }
}
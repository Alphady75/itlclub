<?php

namespace App\DataFixtures;

use App\Entity\Offres;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OffresFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($offres = 1; $offres <= 50; $offres++){
            $user = $this->getReference('user_'. $faker->numberBetween(1, 50));
            $categorie = $this->getReference('categorie_'. $faker->numberBetween(1, 5));
            //$company = $this->getReference('company_'. $faker->numberBetween(1, 50));

            $offre = new Offres();

            $offre->setName($faker->realText(30));
            $offre->setDescription($faker->realText(40));
            $offre->setSlug('lorem-ipsum-dolor-sit-amet-consectetur-' . 'slug-offre-' . $offres);
            $offre->setUser($user);
            $offre->setCategorieoffre($categorie);
            $offre->setVisibility($faker->numberBetween(0, 1));
            $offre->setDeleted($faker->numberBetween(0, 1));
            $offre->setContenu($faker->realText(800));
            //$offre->setCompagny($company);

            $manager->persist($offre);
        }
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            CompanyFixtures::class,
            CategorieOffreFixtures::class,
        ];
    }
}

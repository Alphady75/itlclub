<?php

namespace App\DataFixtures;

use App\Entity\CategorieOffre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategorieOffreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($categories = 1; $categories <= 5; $categories++){

            $categorie = new CategorieOffre();

            $categorie->setName($faker->realText(15));
            $categorie->setSlug('categorie-' . $categories);

            $manager->persist($categorie);

            // Enregistre dans une référence
            $this->addReference('categorie_'. $categories, $categorie);
        }
        

        $manager->flush();
    }
}

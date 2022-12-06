<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for($nbUsers = 1; $nbUsers <= 50; $nbUsers++){
            $user = new User();
            if($nbUsers === 1){
                $user->setEmail('admin@gmail.com');
                $user->setRoles(['ROLE_ADMIN']);
            }
            else{
                $user->setEmail($faker->email);
                $user->setRoles(['ROLE_ADHERANT']);
            }
            $user->setName($faker->firstName());
            $user->setNumeroCompte($faker->numberBetween(10000, 100000));
            $user->setPartenaire($faker->numberBetween(0, 1));
            $user->setValidateNumCompte($faker->numberBetween(0, 1));
            $user->setLastName($faker->lastName());
            $user->setIsVerified($faker->numberBetween(0, 1));
            $user->setPassword($this->encoder->hashPassword($user, 'azerty'));
            $manager->persist($user);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('user_'. $nbUsers, $user);
        }

        $manager->flush();
    }
}

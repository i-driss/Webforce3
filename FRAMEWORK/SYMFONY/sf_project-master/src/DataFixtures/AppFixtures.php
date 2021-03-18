<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Faker;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        // $user = new User(); // Appelle de l'entity user
        // $user->setEmail('axel@axessweb.fr');
        // $user->setNickname('axessweb');
        // $user->setRoles(['ROLE_USER']);
        $faker = \Faker\Factory::create();
        
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setNickname($faker->lastName);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                '123456'
            ));
            
            $manager->persist($user);
            $manager->flush();
          }


        // $manager->flush();
    }
}

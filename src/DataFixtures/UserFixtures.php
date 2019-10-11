<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++){
            $user = new User();
            $user
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setAddress($faker->address)
                ->setEmail($faker->email)
                ->setPhone('0' . rand(1,9) . rand(10000000, 99999999))
                ->setClient($this->getReference('client-' . rand(0, 2)));
            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     * @return array
     */
    public function getDependencies()
    {
        return [ClientFixtures::class];
    }
}

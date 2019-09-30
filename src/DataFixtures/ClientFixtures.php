<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    private $clients = ['Orange', 'SFR', 'Bouygues'];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= count($this->clients)-1; $i++){
            $client = new Client();
            $password = 'password' . $this->clients[$i];
            $client
                ->setName($this->clients[$i])
                ->setPassword(password_hash($password, PASSWORD_DEFAULT))
                ->setRoles(['ROLE_ADMIN']);

            $manager->persist($client);
            $this->addReference('client-' . $i, $client);
        }

        $manager->flush();
    }
}

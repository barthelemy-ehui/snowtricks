<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\User;

class UserFixtures extends Fixture implements OrderedFixtureInterface
{
    public const USER = 'user';
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for($i=0;$i<10;$i++){
           $user = new User();
           $user->setFirstname($faker->firstName);
           $user->setLastname($faker->lastName);
           $user->setUsername($faker->userName);
           $user->setEmail($faker->email);
           $user->setPassword(password_hash($faker->password, PASSWORD_BCRYPT));
           $user->setToken(md5(uniqid('token', false)));
           $user->setCreatedAt($faker->dateTime);
           $user->setUpdatedAt($faker->dateTime);
           $user->setIsActive(false);
           $manager->persist($user);
           $this->setReference(self::USER, $user);
        }
    
        $manager->flush();
    }
    
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Trick;

class TrickFixtures extends Fixture implements /*DependentFixtureInterface,*/ OrderedFixtureInterface
{
    public const TRICK = 'trick';
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for($i=0;$i<2;$i++){
            $trick = new Trick();
            $trick->setName($faker->name);
            $trick->setDescription($faker->sentence);
            $trick->setCreatedAt($faker->dateTime);
            $trick->setUpdatedAt($faker->dateTime);
            $trick->setUser($this->getReference(UserFixtures::USER));
            $trick->setCategory($this->getReference(CategoryFixtures::CATEGORY));
            $trick->setSlug($faker->slug);
            $manager->persist($trick);
    
            $this->setReference(self::TRICK, $trick);
        }
        
        $manager->flush();
    }
    
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
   /* public function getDependencies()
    {
        return [
          UserFixtures::class,
          CategoryFixtures::class,
        ];
    }*/
    
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}

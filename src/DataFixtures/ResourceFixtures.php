<?php

namespace App\DataFixtures;

use App\Entity\Resource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ResourceFixtures extends Fixture implements OrderedFixtureInterface
{
    public const RESOURCE = 'resource';
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $resource = new Resource();
        $resource->setPath($faker->imageUrl());
        $resource->setType(Resource::IMAGE);
        $resource->setCreatedAt($faker->dateTime);
        $resource->setUpdatedAt($faker->dateTime);
        $resource->setTrick($this->getReference(TrickFixtures::TRICK));
        $manager->persist($resource);
        $this->setReference(self::RESOURCE, $resource);
    
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
         TrickFixtures::class
       ];
    }*/
    
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}

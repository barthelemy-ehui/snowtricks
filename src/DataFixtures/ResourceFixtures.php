<?php

namespace App\DataFixtures;

use App\Entity\Resource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ResourceFixtures extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
    
        $faker = Factory::create();
        $resource = new Resource();
        $resource->setName($faker->imageUrl(286,180));
        $resource->setType(Resource::IMAGE);
        $resource->setTrick($this->getReference(TrickFixtures::TRICK));
    
        $manager->flush();
    }
    
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

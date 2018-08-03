<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture implements  OrderedFixtureInterface
{
    public const CATEGORY = 'category';
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        
        for ($i=0;$i<10;$i++){
            $category = new Category();
            $category->setName($faker->name);
            $category->setDescription($faker->sentence);
            $category->setCreatedAt($faker->dateTime);
            $category->setUpdatedAt($faker->dateTime);
            $category->setUser($this->getReference(UserFixtures::USER));
    
            $manager->persist($category);
            $this->setReference(self::CATEGORY, $category);
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
          UserFixtures::class
        ];
    }*/
    
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture implements OrderedFixtureInterface
{
    public const COMMENT = 'comment';
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for($i = 0; $i<2; $i++){
            $comment = new Comment();
            $comment->setContent($faker->sentence);
            $comment->setCreatedAt($faker->dateTime);
            $comment->setUser($this->getReference(UserFixtures::USER));
            $comment->setTrick($this->getReference(TrickFixtures::TRICK));
            $manager->persist($comment);
            $this->setReference(self::COMMENT, $comment);
        }
        
    
        $manager->flush();
    }
    
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
   /*public function getDependencies()
    {
        return [
          UserFixtures::USER,
          TrickFixtures::TRICK,
        ];
    }*/
    
    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}

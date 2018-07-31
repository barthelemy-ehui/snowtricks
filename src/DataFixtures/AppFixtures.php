<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\GroupFigure;
use App\Entity\Image;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        
        /*
         Next: Open your new fixtures class and start customizing it.
         Load your fixtures by running: php bin/console doctrine:fixtures:load
         Docs: https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
        
         Next: Review the new migration "src/Migrations/Version20180731133735.php"
         Then: Run the migration with php bin/console doctrine:migrations:migrate
         See https://symfony.com/doc/current/bundles/DoctrineMigrationsBundle/index.html
        */
        
        $faker = Factory::create();
        for($i= 0; $i<10;$i++) {
            
            $user = new User();
            $user->setFirstname($faker->firstName);
            $user->setLastname($faker->lastName);
            $user->setUsername($faker->username);
            $user->setEmail($faker->email);
            $user->setPassword(password_hash($faker->password,PASSWORD_BCRYPT));
            $user->setToken($faker->numerify());
            $user->setCreatedAt($faker->dateTime);
            $user->setUpdatedAt($faker->dateTime);
            $manager->persist($user);
    
            $group = new GroupFigure();
            $group->setName($faker->name);
            $group->setDescription($faker->sentence);
            $group->setUser($user);
            $group->setCreatedAt($faker->dateTime);
            $group->setUpdatedAt($faker->dateTime);
            $manager->persist($group);
            
    
            for($f = 0; $f<2; $f++) {
    
                $comment = new Comment();
                $comment->setContent($faker->sentence);
                $comment->setCreatedAt($faker->dateTime);
                $comment->setUser($user);
                $manager->persist($comment);
                
                $figure = new Figure();
                $figure->setName($faker->name);
                $figure->setDescription($faker->sentence);
                $figure->setCreatedAt($faker->dateTime);
                $figure->setUpdatedAt($faker->dateTime);
                $figure->setUser($user);
                $figure->addComment($comment);
                $figure->setGroupFigure($group);
                $manager->persist($figure);
                
                $image = new Image();
                $image->setPath($faker->imageUrl());
                $image->setCreatedAt($faker->dateTime);
                $image->setUpdatedAt($faker->dateTime);
                $image->setFigure($figure);
                $manager->persist($image);
                
                $video = new Video();
                $video->setPath($faker->imageUrl());
                $video->setCreatedAt($faker->dateTime);
                $video->setUpdatedAt($faker->dateTime);
                $video->setFigure($figure);
                $manager->persist($video);
            }
        }
        
        $manager->flush();
    }
}

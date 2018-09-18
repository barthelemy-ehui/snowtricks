<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Resource;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TrickTest extends TestCase
{
    private $trick;
    
    public function setUp() {
        
        $this->trick = new Trick();
    }
    
    public function testName() {
        $name = 'Flip Inside-Out';
        $this->trick->setName($name);
        $this->assertEquals($name, $this->trick->getName());
    }
    
    
    public function testDescription() {
    
        $description = 'Nice flip around';
        $this->trick->setDescription($description);
        $this->assertEquals($description, $this->trick->getDescription());
    
    }
    
    public function testSlug() {
        $slug = 'awesome-flip';
        $this->trick->setSlug($slug);
        $this->assertEquals($slug, $this->trick->getSlug());
    }
    
    public function testUser() {
        $user = new User();
        $this->trick->setUser($user);
        $this->assertEquals($user, $this->trick->getUser());
    }

    public function testCategory() {
        $category = new Category();
        $this->trick->setCategory($category);
        $this->assertEquals($category, $this->trick->getCategory());
    }
    
    
    public function testIfContainsComments() {
        
        $comment = new Comment();
        $this->trick->addComment($comment);
        $this->assertContains($comment, $this->trick->getComments());
    }
    
    public function testIfContainsResource() {
        $resource = new Resource();
        $this->trick->addResource($resource);
        $this->assertContains($resource, $this->trick->getResources());
    }
}

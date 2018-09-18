<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private $category;
    private $user;
    private $tricks;
    
    public function setUp()
    {
        $this->category = new Category();
        $this->user = new User();
        $this->tricks = new ArrayCollection();
    }
    
    public function testIfNameIsNotEmpty() {
        $name = 'flip';
        $this->category->setName($name);
        $this->assertNotEmpty($this->category->getName());
    }
    
    public function testIfDescriptionIsNotEmpty() {
        $description = 'The best flip ever';
        $this->category->setDescription($description);
        $this->assertNotEmpty($this->category->getDescription());
    }
    
    public function testUser() {
        $user = new User();
        $this->category->setUser($user);
        $this->assertEquals($user, $this->category->getUser());
    }
    
    public function testIfContainsTricks() {
        $trick = new Trick();
        $this->category->addTrick($trick);
        $this->assertContains($trick, $this->category->getTricks());
    }
}

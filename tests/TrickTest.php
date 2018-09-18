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
    
    public function testIfIdIsNotNull(){
        $id = 1;
        $this->trick->setId($id);
        $this->assertNotEmpty($this->trick->getId());
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
    
    public function testIfCreatedAtIsNotNull() {
        $this->trick->setCreatedAt(new \DateTime());
        $this->assertNotNull($this->trick->getCreatedAt());
    }
    
    public function testIfUpdatedAtIsNotNull() {
        $this->trick->setUpdatedAt(new \DateTime());
        $this->assertNotNull($this->trick->getUpdatedAt());
    }
    
    public function testIfCommentHasBeenRemoved(){
        $comment = new Comment();
        $this->trick->addComment($comment);
        $this->trick->removeComment($comment);
        $this->assertNotContains($comment, $this->trick->getComments());
    }
    
    public function testIfResourceHasBeenRemoved(){
        $resource = new Resource();
        $this->trick->addResource($resource);
        $this->trick->removeResource($resource);
        $this->assertNotContains($resource, $this->trick->getResources());
    }
    
    public function testIfPrincipalNull(){
        $this->assertNull($this->trick->getPrincipal());
    }

    public function testIfPrincipalExist(){
        $resource = new Resource();
        $resource->setPrincipal(true);
        $this->trick->addResource($resource);
        
        $this->assertEquals($resource, $this->trick->getPrincipal());
    }
    
    public function testIfThumbnailOrDefaultIsNull(){
        $this->assertNull($this->trick->getThumbnailOrDefault());
    }
    
    public function testIfThumbnailOrDefaultIsNotEmpty() {
        
        $resource = new Resource();
        $resource->setName('pic.jpg');
        $this->trick->addResource($resource);
        $this->assertNotEmpty($this->trick->getThumbnailOrDefault());
    
    }
    
    public function testIfThumbnailOrDefaultPrincipalIsNotEmpty() {
        
        $resource = new Resource();
        $resource->setName('pic.jpg');
        $resource->setPrincipal(true);
        $this->trick->addResource($resource);
        $this->assertNotEmpty($this->trick->getThumbnailOrDefault());
    
    }
}

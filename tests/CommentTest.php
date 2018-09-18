<?php

namespace App\Tests;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private $comment;
    
    
    public function setUp()
    {
        $this->comment = new Comment();
    }
    
    public function testIfIdIsNotNull(){
        $id = 1;
        $this->comment->setId($id);
        $this->assertNotEmpty($this->comment->getId());
    }
    
    public function testIfContentNotEmpty()
    {
        $content = 'This is a cool flip outside down';
        $this->comment->setContent($content);
        $this->assertNotEmpty($this->comment->getContent());
    }
    
    
    public function testUser() {
        $user = new User();
        $this->comment->setUser($user);
        $this->assertEquals($user, $this->comment->getUser());
    }
    
    public function testTrick() {
        $trick = new Trick();
        $this->comment->setTrick($trick);
        $this->assertEquals($trick, $this->comment->getTrick());
    }
    
    public function testIfCreatedAtIsNotNull() {
        $this->comment->setCreatedAt(new \DateTime());
        $this->assertNotNull($this->comment->getCreatedAt());
    }
}

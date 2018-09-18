<?php

namespace App\Tests;

use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private $user;
    
    public function setUp() {
        $this->user = new User();
    }
    
    
    public function testFirstname() {
        
        $firstname = 'John';
        $this->user->setFirstname($firstname);
        $this->assertEquals($firstname, $this->user->getFirstname());
    }
    
    
    public function testLastname() {
        $lastname = 'Doe';
        $this->user->setLastname($lastname);
        $this->assertEquals($lastname, $this->user->getLastname());
    }

    public function testUsername() {
        
        $username = 'john.doe';
        $this->user->setUsername($username);
        $this->assertEquals($username, $this->user->getUsername());
        
    }
    
    public function testEmail() {
        
        $email = 'joe.doe@gmail.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
    
    }
    
    
    
    public function testToken() {
        $token = 'ZAERZEGFDFDSF12DGFDHG';
        $this->user->setToken($token);
        $this->assertEquals($token, $this->user->getToken());
    }
    
    
    public function testTricks() {
        
        $trick = new Trick();
        $this->user->addTrick($trick);
        $this->assertContains($trick, $this->user->getTricks());
        
    }
    
    public function testRoleUser() {
        
        $role = [User::ROLE_USER];
        $this->user->setRoles($role);
        $this->assertEquals($role, $this->user->getRoles());
        
    }

    public function testIfIsActive() {
        
        $isActive = true;
        $this->user->setIsActive($isActive);
        $this->assertEquals($isActive, $this->user->getisActive());
        
    }
    
    public function testIfIsNotActive() {
        
        $isActive = false;
        $this->user->setIsActive($isActive);
        $this->assertEquals($isActive, $this->user->getisActive());
    
    }
    
    public function testPicture() {
    
        $picture = 'profile.png';
        $this->user->setPicture($picture);
        $this->assertEquals($picture, $this->user->getPicture());
    }
    
    public function testPassword() {
        
        $password = '7654321';
        $this->user->setPassword($password);
        $this->assertEquals($password, $this->user->getPassword());
    }

}

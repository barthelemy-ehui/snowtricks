<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Comment;
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
    
    public function testIfIdIsNotNull(){
        $id = 1;
        $this->user->setId($id);
        $this->assertNotEmpty($this->user->getId());
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
    
    public function testIfCreatedAtIsNotNull() {
        $this->user->setCreatedAt(new \DateTime());
        $this->assertNotNull($this->user->getCreatedAt());
    }
    
    public function testIfUpdatedAtIsNotNull() {
        $this->user->setUpdatedAt(new \DateTime());
        $this->assertNotNull($this->user->getUpdatedAt());
    }
    
    public function testIfCommentHasBeenRemoved(){
        $comment = new Comment();
        $this->user->addComment($comment);
        $this->user->removeComment($comment);
        $this->assertNotContains($comment, $this->user->getComments());
    }
    
    public function testIfCategoryHasBeenRemoved(){
        $category = new Category();
        $this->user->addCategory($category);
        $this->user->removeCategory($category);
        $this->assertNotContains($category, $this->user->getCategories());
    }
    
    public function testIfTrickHasBeenRemoved() {
        $trick = new Trick();
        $this->user->addTrick($trick);
        $this->user->removeTrick($trick);
        $this->assertNotContains($trick, $this->user->getTricks());
    }
    
    public function testCurrentPassword() {
        $currentPassword = '7654321';
        $this->user->setCurrentPassword($currentPassword);
        $this->assertEquals($currentPassword, $this->user->getCurrentPassword());
    }

    public function testChangePassword() {
        $changePassword = '7654321';
        $this->user->setChangePassword($changePassword);
        $this->assertEquals($changePassword, $this->user->getChangePassword());
    
    }
    
    public function testIfSaltIsNull() {
        $this->assertNull($this->user->getSalt());
    }
    
    public function testIfPlainPasswordIsNull() {
        
        $this->user->setPlainPassword('76543321');
        $this->user->eraseCredentials();
        $this->assertNull($this->user->getPlainPassword());
    }
    
    public function testSerialize() {
        $this->user->setId(1);
        $this->user->setUsername('username');
        $this->user->setPassword('765432');
        
        $serialize = serialize([
           $this->user->getId(),
           $this->user->getUsername(),
           $this->user->getPassword()
        ]);
        
        $this->assertEquals($serialize, $this->user->serialize());
    }
    
    public function testUnserialize() {
        $id = 1;
        $username = 'username';
        $password = '765432';
        
        $this->user->setId($id);
        $this->user->setUsername($username);
        $this->user->setPassword($password);
    
        $serialize = serialize([
            $this->user->getId(),
            $this->user->getUsername(),
            $this->user->getPassword()
        ]);
    
        $this->user->unserialize($serialize);
        $this->assertEquals($id, $this->user->getId());
        $this->assertEquals($username, $this->user->getUsername() );
        $this->assertEquals($password, $this->user->getPassword());
    
    }
}

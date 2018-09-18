<?php

namespace App\Tests;

use App\Entity\Resource;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    private $resource;
    
    public function setUp()
    {
        $this->resource = new Resource();
    }
    
    public function testNameIfIsNotEmpty() {
        $name = 'superflip.jpg';
        $this->resource->setName($name);
        $this->assertNotEmpty($this->resource->getName());
    }
    
    public function testTypeIfVideo() {
        $this->resource->setType(Resource::VIDEO);
        $this->assertEquals(Resource::VIDEO, $this->resource->getType());
    }
    
    public function testTypeIfImg() {
        $this->resource->setType(Resource::IMAGE);
        $this->assertEquals(Resource::IMAGE, $this->resource->getType());
    }
    
    
    public function testTrick() {
        $trick = new Trick();
        $this->resource->setTrick($trick);
        $this->assertEquals($trick, $this->resource->getTrick());
    }
    
    
    public function testIfPrincipalIsFalse() {
        $principal = false;
        $this->resource->setPrincipal($principal);
        $this->assertEquals($principal, $this->resource->getPrincipal());
    }
    
    public function testIfPrincipalIsTrue() {
        $principal = true;
        $this->resource->setPrincipal($principal);
        $this->assertEquals($principal, $this->resource->getPrincipal());
    }
}

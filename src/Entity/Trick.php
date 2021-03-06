<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 * @UniqueEntity(
 *     fields={"slug"},
 *     message="Ce slug est déjà utilisé"
 * )
 */
class Trick
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Champs vide")
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message="Champs vide")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tricks")
     */
    private $User;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Pas de sélection")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="trick")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Pas de sélection")
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resource", mappedBy="trick", cascade={"persist","merge","remove","refresh"})
     */
    private $resources;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->resources = new ArrayCollection();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
    
    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->setTrick($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
            // set the owning side to null (unless already changed)
            if ($resource->getTrick() === $this) {
                $resource->setTrick(null);
            }
        }

        return $this;
    }
    
    public function getPrincipal() {
        
        $principal = null;
        
        foreach($this->resources as $resource) {
            if($resource->getPrincipal()){
                $principal = $resource;
                break;
            }
        }
        return $principal;
    }
    
    public function getThumbnailOrDefault() {
        
        if($this->resources->count() > 0) {
            $principal = $this->getPrincipal();
            if($principal) {
                return ['type' => $principal->getType(),'name'=> $principal->getName()];
            }
            $first  = $this->resources->first();
            return ['type' => $first->getType(), 'name' => $first->getName()];
        }
        
        return null;
    }
}

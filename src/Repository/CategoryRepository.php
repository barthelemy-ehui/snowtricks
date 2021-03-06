<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }
    
    public function save($data){
        $em = $this->getEntityManager();
        if(is_null($data->getId())){
            $em->persist($data);
        } else {
            $em->merge($data);
        }
        
        $em->flush();
        
        return $data;
        
    }
    
    public function delete(int $id){
        $em = $this->_em;
        $category = $em->getRepository(Category::class)->findOneBy(['id'=>$id]);
        $em->remove($category);
        $em->flush();
    }
}

<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }
    
    public function paginator($page) {
        
        $maxResult = 15;
        $dql = 'SELECT t FROM App\Entity\Trick t ORDER BY t.id DESC';
        $query = $this->_em->createQuery($dql)
        ->setFirstResult($page*$maxResult)
        ->setMaxResults($maxResult);
        
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        return $paginator;
    }

    public function save($data) {
        $em = $this->getEntityManager();
        if(is_null($data->getId())){
            $em->persist($data);
        } else {
           $em->merge($data);
        }
        
        $em->flush();
        
        return $data;
    }
    
    public function delete($trick)
    {
        $em = $this->getEntityManager();
        $em->remove($trick);
        $em->flush();
    }
}

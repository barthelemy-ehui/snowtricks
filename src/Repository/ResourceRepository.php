<?php

namespace App\Repository;

use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Resource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resource[]    findAll()
 * @method Resource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Resource::class);
    }
    
    public function save(Resource $resourceFound)
    {
        $this->_em->persist($resourceFound);
        $this->_em->flush();
    }
    
    public function delete(Resource $resource)
    {
        $em = $this->getEntityManager();
        $em->remove($resource);
        $em->flush();
    }

}

<?php

namespace App\Repository;

use App\Entity\GroupFigure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GroupFigure|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupFigure|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupFigure[]    findAll()
 * @method GroupFigure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupFigureRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupFigure::class);
    }

//    /**
//     * @return GroupFigure[] Returns an array of GroupFigure objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GroupFigure
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\MoviesList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MoviesList|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoviesList|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoviesList[]    findAll()
 * @method MoviesList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoviesListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoviesList::class);
    }

    // /**
    //  * @return MoviesList[] Returns an array of MoviesList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MoviesList
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

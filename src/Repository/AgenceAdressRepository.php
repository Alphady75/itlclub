<?php

namespace App\Repository;

use App\Entity\AgenceAdress;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AgenceAdress|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgenceAdress|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgenceAdress[]    findAll()
 * @method AgenceAdress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgenceAdressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgenceAdress::class);
    }

    // /**
    //  * @return AgenceAdress[] Returns an array of AgenceAdress objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AgenceAdress
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

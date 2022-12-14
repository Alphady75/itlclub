<?php

namespace App\Repository;

use App\Entity\ComplementaireInfos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ComplementaireInfos|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComplementaireInfos|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComplementaireInfos[]    findAll()
 * @method ComplementaireInfos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplementaireInfosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComplementaireInfos::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ComplementaireInfos $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ComplementaireInfos $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByDateDesc($limit = null)
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.created', 'DESC')
            ->setMaxResults($limit ? $limit : 999999999)
            ->getQuery()
            ->getResult()
        ;
    }
}

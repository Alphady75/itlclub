<?php

namespace App\Repository;

use App\Entity\Offres;
use App\Entity\SearchOffres;
use App\Entity\CategorieOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Offres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offres[]    findAll()
 * @method Offres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffresRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */ 
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Offres::class);

        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Offre $entity, bool $flush = true): void
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
    public function remove(Offre $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByDateDesc($limit = null)
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.created', 'DESC')
            ->setMaxResults($limit ? $limit : 999999999)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByDateDescForView($limit = null)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.visibility = :visibility')
            ->setParameter('visibility', 1)
            ->orderBy('o.created', 'DESC')
            ->setMaxResults($limit ? $limit : 999999999)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByCategorieDateDesc($categorie)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.visibility = :visibility')
            ->andWhere('o.categorieoffre = :categorieoffre')
            ->setParameters([
                'visibility' => 1,
                'categorieoffre' => $categorie
            ])
            ->orderBy('o.created', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Recupère les annonces en lien avec une recherche
     * @return PaginationInterface
     */
    public function findSearch(SearchOffres $search): PaginationInterface
    {
        $query = $this->getSearcheQuery($search)->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            21
        );

    }

    /**
     * Recupère les annonces en lien avec une recherche
     * @return PaginationInterface
     */
    public function findSearchByCategorie(SearchOffres $search, CategorieOffre $categorie): PaginationInterface
    {
        $query = $this->getSearcheQueryByCategorie($search, $categorie)->getQuery();

        return $this->paginator->paginate(
            $query,
            $search->page,
            21
        );

    }

    /**
     * //@return QueryBuilder
     */
    private function getSearcheQuery(SearchOffres $search)
    {
        $query = $this->createQueryBuilder('o')
        ->orderBy('o.created', 'DESC')
        ->andWhere('o.visibility = 1');

        if(!empty($search->q)){
            $query = $query
            ->andWhere('o.name LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        }

        return $query;
    }

    /**
     * //@return QueryBuilder
     */
    private function getSearcheQueryByCategorie(SearchOffres $search, CategorieOffre $categorie)
    {
        $query = $this->createQueryBuilder('o')
        ->orderBy('o.created', 'DESC')
        ->andWhere('o.visibility = 1')
        ->andWhere('o.categorieoffre = :categorieoffre')
        ->setParameter('categorieoffre', $categorie);

        if(!empty($search->q)){
            $query = $query
            ->andWhere('o.name LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        }

        return $query;
    }

    // /**
    //  * @return Offres[] Returns an array of Offres objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offres
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

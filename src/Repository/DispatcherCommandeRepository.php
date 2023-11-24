<?php

namespace App\Repository;

use App\Entity\DispatcherCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DispatcherCommande>
 *
 * @method DispatcherCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method DispatcherCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method DispatcherCommande[]    findAll()
 * @method DispatcherCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DispatcherCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DispatcherCommande::class);
    }

    public function add(DispatcherCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(DispatcherCommande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return DispatcherCommande[] Returns an array of DispatcherCommande objects
     */
    public function findByDate($dispatcherId, $date_star, $date_end): array
    {

        return $this->createQueryBuilder('d')
            ->andWhere('d.dispatcherId = :id')
            ->andWhere('d.date BETWEEN :date_star AND :date_end')
            ->setParameter('id', $dispatcherId)
            ->setParameter('date_star', $date_star)
            ->setParameter('date_end', $date_end)
            ->orderBy('d.date', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?DispatcherCommande
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

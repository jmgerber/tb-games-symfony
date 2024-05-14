<?php

namespace App\Repository;

use App\Entity\Games;
use App\Entity\Riddle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Riddle>
 *
 * @method Riddle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Riddle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Riddle[]    findAll()
 * @method Riddle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RiddleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Riddle::class);
    }

    public function findByGame(Games $game)
    {
        return $this->createQueryBuilder('e')
            ->where('e.game = :game')
            ->setParameter('game', $game)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Riddle[] Returns an array of Riddle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Riddle
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

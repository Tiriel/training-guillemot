<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Movie\Consumer\Enum\SearchType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findLikeOmdb(string $value, SearchType $type): ?Movie
    {
        $qb = $this->getWhereClauseForType($value, $type);

        return $qb->orderBy('m.releasedAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    private function getWhereClauseForType(string $value, SearchType $type): QueryBuilder
    {
        $qb = $this->createQueryBuilder('m');

        if (SearchType::Title === $type) {
            $qb->andWhere($qb->expr()->like('m.title', ':value'))
                ->setParameter('value', "%$value%");

            return $qb;
        }

        $qb->andWhere($qb->expr()->eq('m.imdbId', ':value'))
            ->setParameter('value', $value);

        return $qb;
    }

    //    /**
    //     * @return Movie[] Returns an array of Movie objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Movie
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

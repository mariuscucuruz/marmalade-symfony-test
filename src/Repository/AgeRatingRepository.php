<?php

namespace App\Repository;

use App\Entity\AgeRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AgeRating>
 *
 * @method AgeRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgeRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgeRating[]    findAll()
 * @method AgeRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgeRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgeRating::class);
        $repository = $registry->getRepository(AgeRating::class);
    }

    public function getForAge(int $age): array
    {
        /** @var \Illuminate\Database\Eloquent\Model $ageFactor */
        $ageFactor = $this->createQueryBuilder->where('age', $age)->firstOrFail();

        return $ageFactor->toArray();
    }


    /*
    public function findOneBySomeField($value): ?AgeRating
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

<?php

namespace App\Repository;

use App\Entity\SecretCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SecretCategory>
 *
 * @method SecretCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecretCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecretCategory[]    findAll()
 * @method SecretCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecretCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecretCategory::class);
    }

    public function save(SecretCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SecretCategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SecretCategory[] Returns an array of SecretCategory objects
//     */
    public function findByUuid($value): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :val')
            ->setParameter('val', $value)
            ->orderBy('s.user', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?SecretCategory
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

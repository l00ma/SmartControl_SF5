<?php

namespace App\Repository;

use App\Entity\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Security>
 *
 * @method Security|null find($id, $lockMode = null, $lockVersion = null)
 * @method Security|null findOneBy(array $criteria, array $orderBy = null)
 * @method Security[]    findAll()
 * @method Security[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Security::class);
    }

    public function add(Security $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Security $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Security[]
     */
    public function findByMedia($value): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.time_stamp')
            ->andWhere('s.file_type = :type')
            ->setParameter('type', $value)
            ->orderBy('s.time_stamp', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findAllMedia($value): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.filename, s.id')
            ->andWhere('s.file_type = :type')
            ->setParameter('type', $value)
            ->orderBy('s.time_stamp', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

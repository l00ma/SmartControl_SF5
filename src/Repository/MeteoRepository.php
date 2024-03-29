<?php

namespace App\Repository;

use App\Entity\Meteo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meteo>
 *
 * @method Meteo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meteo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meteo[]    findAll()
 * @method Meteo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeteoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meteo::class);
    }

    public function add(Meteo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Meteo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

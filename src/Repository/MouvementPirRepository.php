<?php

namespace App\Repository;

use App\Entity\MouvementPir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MouvementPir>
 *
 * @method MouvementPir|null find($id, $lockMode = null, $lockVersion = null)
 * @method MouvementPir|null findOneBy(array $criteria, array $orderBy = null)
 * @method MouvementPir[]    findAll()
 * @method MouvementPir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MouvementPirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MouvementPir::class);
    }

    public function add(MouvementPir $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MouvementPir $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

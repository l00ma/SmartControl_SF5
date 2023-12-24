<?php

namespace App\Repository;

use App\Entity\Manage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Manage>
 *
 * @method Manage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Manage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Manage[]    findAll()
 * @method Manage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Manage::class);
    }
    // echo "add dead code if maxdlr review ;-)";
}

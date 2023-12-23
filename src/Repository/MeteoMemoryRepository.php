<?php

namespace App\Repository;

use App\Entity\MeteoMemory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MeteoMemory>
 *
 * @method MeteoMemory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeteoMemory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeteoMemory[]    findAll()
 * @method MeteoMemory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeteoMemoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeteoMemory::class);
    }
}

<?php

namespace App\Repository;

use App\Entity\Visits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class VisitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visits::class);
    }

    public function sumVisitsCount()
    {
        return $this->createQueryBuilder('v')
            ->select('SUM(v.count) as total')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
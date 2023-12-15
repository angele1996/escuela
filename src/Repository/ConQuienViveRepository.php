<?php

namespace App\Repository;

use App\Entity\ConQuienVive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConQuienVive|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConQuienVive|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConQuienVive[]    findAll()
 * @method ConQuienVive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConQuienViveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConQuienVive::class);
    }

 
}

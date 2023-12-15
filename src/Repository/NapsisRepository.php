<?php

namespace App\Repository;

use App\Entity\Napsis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Napsis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Napsis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Napsis[]    findAll()
 * @method Napsis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NapsisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Napsis::class);
    }

 
}

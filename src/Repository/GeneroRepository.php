<?php

namespace App\Repository;

use App\Entity\Genero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Genero|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genero|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genero[]    findAll()
 * @method Genero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeneroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genero::class);
    }

  
}

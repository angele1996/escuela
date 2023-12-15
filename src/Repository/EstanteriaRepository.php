<?php

namespace App\Repository;

use App\Entity\Estanteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Estanteria|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estanteria|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estanteria[]    findAll()
 * @method Estanteria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstanteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estanteria::class);
    }

   
}

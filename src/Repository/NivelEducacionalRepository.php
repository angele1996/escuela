<?php

namespace App\Repository;

use App\Entity\NivelEducacional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NivelEducacional|null find($id, $lockMode = null, $lockVersion = null)
 * @method NivelEducacional|null findOneBy(array $criteria, array $orderBy = null)
 * @method NivelEducacional[]    findAll()
 * @method NivelEducacional[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NivelEducacionalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NivelEducacional::class);
    }

    
}

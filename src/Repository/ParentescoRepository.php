<?php

namespace App\Repository;

use App\Entity\Parentesco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Parentesco|null find($id, $lockMode = null, $lockVersion = null)
 * @method Parentesco|null findOneBy(array $criteria, array $orderBy = null)
 * @method Parentesco[]    findAll()
 * @method Parentesco[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParentescoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Parentesco::class);
    }

  
}

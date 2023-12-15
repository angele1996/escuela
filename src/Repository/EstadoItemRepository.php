<?php

namespace App\Repository;

use App\Entity\EstadoItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EstadoItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoItem[]    findAll()
 * @method EstadoItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoItem::class);
    }

  
}

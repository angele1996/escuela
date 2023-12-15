<?php

namespace App\Repository;

use App\Entity\TipoItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoItem[]    findAll()
 * @method TipoItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoItem::class);
    }

 
}

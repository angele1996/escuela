<?php

namespace App\Repository;

use App\Entity\EstadoLibro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EstadoLibro|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstadoLibro|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstadoLibro[]    findAll()
 * @method EstadoLibro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstadoLibroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstadoLibro::class);
    }

    
}

<?php

namespace App\Repository;

use App\Entity\TipoPersona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TipoPersona|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipoPersona|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipoPersona[]    findAll()
 * @method TipoPersona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipoPersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipoPersona::class);
    }

 
}

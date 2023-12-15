<?php

namespace App\Repository;

use App\Entity\ConfiguracionCodigo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConfiguracionCodigo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfiguracionCodigo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfiguracionCodigo[]    findAll()
 * @method ConfiguracionCodigo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfiguracionCodigoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfiguracionCodigo::class);
    }

    public function findConfiguracion(): ?ConfiguracionCodigo
    {
        return $this->createQueryBuilder('c')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
   
}

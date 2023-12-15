<?php

namespace App\Repository;

use App\Entity\Ejemplar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ejemplar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ejemplar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ejemplar[]    findAll()
 * @method Ejemplar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EjemplarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ejemplar::class);
    }

    public function findNextCodigoAleatorio()
    {
        $q = $this->createQueryBuilder('e');
        $q->select('MAX(e.codigo) as maximo');  
        $max = $q->getQuery()->getSingleScalarResult();
        if($max == null)
        {
            $max = 14000;
        }
        return $max+1;
    }

    public function findCodigosNoImpresos()
    {
        $q = $this->createQueryBuilder('e');
        $q = $q->andWhere('e.activo = TRUE');
        $q = $q->andWhere('e.codigoImpreso = FALSE');
        return $q->getQuery()->getResult();
    }

    public function findCodigosNoImpresosByFecha(\DateTime $ahora)
    {
        $q = $this->createQueryBuilder('e');
        $q = $q->andWhere('e.activo = TRUE');
        $q = $q->andWhere('e.codigoImpreso = FALSE');
        $q = $q->andWhere('e.fechaImpresionCodigo = :ahora');
        $q = $q->setParameter('ahora', $ahora);
        return $q->getQuery()->getResult();
    }
}

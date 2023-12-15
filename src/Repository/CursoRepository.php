<?php

namespace App\Repository;

use App\Entity\Curso;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Curso|null find($id, $lockMode = null, $lockVersion = null)
 * @method Curso|null findOneBy(array $criteria, array $orderBy = null)
 * @method Curso[]    findAll()
 * @method Curso[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CursoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Curso::class);
    }

    public function findCursosVigentes()
    {
        $q = $this->createQueryBuilder('a')
        ->leftJoin('a.anio', 'anio')
        ->andWhere('anio.vigente = TRUE');
        return $q->getQuery()->getResult();
    }

    public function queryBuilderCursosMatricula()
    {
        $q = $this->createQueryBuilder('a')
        ->leftJoin('a.anio', 'anio')
        ->andWhere('anio.matricula = TRUE');
        return $q;
    }

  
}

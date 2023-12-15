<?php

namespace App\Repository;

use App\DTO\MatriculaBuscadorDto;
use App\Entity\Matricula;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matricula|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matricula|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matricula[]    findAll()
 * @method Matricula[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatriculaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matricula::class);
    }

    public function findByBuscador($rut, $curso, $nombres, $apellidos, $matriculaCompletada)
    {
        $q = $this->createQueryBuilder('m')
            ->leftJoin('m.curso', 'c')
            ->leftJoin('c.anio', 'a')
            ->andWhere('m.activo = TRUE')
            ->andWhere('a.matricula = TRUE')
            ->andWhere(':rut = \'\' OR m.rut = :rut')
            ->andWhere(':curso IS NULL OR m.curso = :curso')
            ->andWhere(':nombres IS NULL OR m.nombres LIKE :nombres')
            ->andWhere(':apellidos IS NULL OR m.apellidoPaterno LIKE :apellidos OR m.apellidoMaterno LIKE :apellidos')
            ->andWhere(':matriculaCompletada IS NULL
            OR :matriculaCompletada = \'\'
            OR (:matriculaCompletada = 1 AND m.matriculaCompletada = 1)
            OR (:matriculaCompletada = 0 AND (m.matriculaCompletada IS NULL OR m.matriculaCompletada = 0))')
            ->setParameter('rut', $rut)
            ->setParameter('curso', $curso)
            ->setParameter('matriculaCompletada', $matriculaCompletada);
        if($nombres)
        {
		    $q = $q->setParameter('nombres', '%'.str_replace(' ','%',$nombres.'%'));
        }
        else
        {
		    $q = $q->setParameter('nombres', NULL);
        }
        if($apellidos)
        {
		    $q = $q->setParameter('apellidos', '%'.str_replace(' ','%',$apellidos.'%'));
        }
        else
        {
		    $q = $q->setParameter('apellidos', NULL);
        }
        return $q->orderBy('m.apellidoPaterno', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findEnMatriculaActual($rut)
    {
        $q = $this->createQueryBuilder('m')
        ->leftJoin('m.curso', 'c')
        ->leftJoin('c.anio', 'a')
        ->andWhere('m.rut = :rut')
        ->andWhere('a.matricula = TRUE')
        ->andWhere('m.activo = TRUE')
        ->setParameter('rut', $rut)
        ->orderBy('a.numero', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult()
        ;

        return $q;
    }

    public function findEnMatriculaAnterior($rut)
    {
        $q = $this->createQueryBuilder('m')
        ->leftJoin('m.curso', 'c')
        ->leftJoin('c.anio', 'a')
        ->andWhere('m.rut = :rut')
        ->andWhere('a.matricula = FALSE')
        ->andWhere('m.activo = TRUE')
        ->setParameter('rut', $rut)
        ->orderBy('a.numero', 'DESC')
        ->setMaxResults(1)
        ->getQuery()
        ->getOneOrNullResult()
        ;

        return $q;
    }

  
}

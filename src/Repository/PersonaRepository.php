<?php

namespace App\Repository;

use App\Entity\Curso;
use App\Entity\Persona;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method Persona|null find($id, $lockMode = null, $lockVersion = null)
 * @method Persona|null findOneBy(array $criteria, array $orderBy = null)
 * @method Persona[]    findAll()
 * @method Persona[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Persona::class);
    }

    public function findProfesores()
    {
        $q = $this->createQueryBuilder('a')
        ->leftJoin('a.tipoPersona', 'tp')
        ->andWhere('tp.esResponsable = TRUE');
        return $q->getQuery()->getResult();
    }

    public function findAlumnosByCurso(Curso $curso)
    {
        $q = $this->createQueryBuilder('a')
        ->leftJoin('a.tipoPersona', 'tp')
        ->leftJoin('a.alumnos', 'al')
        ->andWhere('tp.esResponsable = FALSE')
        ->andWhere('al.curso = :curso')
        ->setParameter('curso', $curso)
        ->addOrderBy('a.apellidos', 'ASC')
        ->addOrderBy('a.nombres', 'ASC');
        return $q->getQuery()->getResult();
    }

    public function getUltimaCredencial()
	{
		$rsm = new ResultSetMapping();
		$rsm->addScalarResult('credencial', 'credencial');
		$query = $this->getEntityManager()->createNativeQuery(
            'SELECT MAX(p.credencial) credencial FROM persona p', 
            $rsm);
		return $query->getSingleResult();
    }

  
}

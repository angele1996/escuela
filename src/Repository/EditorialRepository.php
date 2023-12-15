<?php

namespace App\Repository;

use App\Entity\Editorial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @method Editorial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Editorial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Editorial[]    findAll()
 * @method Editorial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditorialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Editorial::class);
    }

    public function findByBuscador($nombre)
    {
        $q = $this->createQueryBuilder('e');
        $q = $q->andWhere('e.nombre LIKE :nombre');
        $q = $q->setParameter('nombre', '%'.str_replace(' ','%',$nombre.'%'));
        return $q->getQuery()->getResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\DTO\BibliotecaBuscadorDto;

/**
 * @method Libro|null find($id, $lockMode = null, $lockVersion = null)
 * @method Libro|null findOneBy(array $criteria, array $orderBy = null)
 * @method Libro[]    findAll()
 * @method Libro[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libro::class);
    }

    public function findByBibliotecaBuscador(BibliotecaBuscadorDto $buscadorDTO)
    {
        $q = $this->createQueryBuilder('l')
        ->leftJoin('l.autors', 'a')
        ->leftJoin('l.ejemplars', 'e')
        ;
        $q = $q->andWhere('l.activo = TRUE');
        if($buscadorDTO->nombre_libro)
        {
            $q = $q->andWhere('l.nombre LIKE :nombre_libro');
            $q = $q->setParameter('nombre_libro', '%'.str_replace(' ','%',$buscadorDTO->nombre_libro.'%'));
        }
        if($buscadorDTO->nombre_autor)
        {
            $q = $q->andWhere('a.nombre LIKE :nombre_autor');
            $q = $q->setParameter('nombre_autor', '%'.str_replace(' ','%',$buscadorDTO->nombre_autor.'%'));
        }
        if($buscadorDTO->codigo_barra)
        {
            $q = $q->andWhere('e.codigo = :codigo_barra');
            $q = $q->setParameter('codigo_barra', $buscadorDTO->codigo_barra);
        }
        if($buscadorDTO->isbn)
        {
            $q = $q->andWhere('l.isbn = :isbn');
            $q = $q->setParameter('isbn', $buscadorDTO->isbn);
        }
        if($buscadorDTO->ubicacion)
        {
            $q = $q->andWhere('e.ubicacion = :ubicacion');
            $q = $q->setParameter('ubicacion', $buscadorDTO->ubicacion);
        }
        return $q->getQuery()->getResult();
    }
}

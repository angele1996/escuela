<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\DTO\BibliotecaBuscadorDto;
use App\DTO\InventarioBuscadorDto;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findInventarioByBuscador(InventarioBuscadorDto $buscadorDTO)
    {
        $q = $this->createQueryBuilder('a')
        ->leftJoin('a.tipoItem', 'ti')
        ->andWhere('ti.esPrestable = FALSE')
        ->andWhere('(:codigo IS NULL OR a.codigo = :codigo)')
        ->andWhere('(:nombre IS NULL OR a.nombre LIKE :nombre)')
        ->andWhere('(:tipoItem IS NULL OR a.tipoItem = :tipoItem)')
        ->andWhere('(:ubicacion IS NULL OR a.ubicacion = :ubicacion)')
        ->setParameter('codigo', $buscadorDTO->codigo);
        if($buscadorDTO->nombre)
        {
		    $q = $q->setParameter('nombre', '%'.str_replace(' ','%',$buscadorDTO->nombre.'%'));
        }
        else
        {
		    $q = $q->setParameter('nombre', NULL);
        }
        $q = $q->setParameter('tipoItem', $buscadorDTO->tipoItem)
            ->setParameter('ubicacion', $buscadorDTO->ubicacion);
        return $q->getQuery()->getResult();
    }

    public function findBibliotecaByBuscador(BibliotecaBuscadorDto $buscadorDTO)
    {
        $q = $this->createQueryBuilder('a')
        ->leftJoin('a.tipoItem', 'ti')
        ->andWhere('ti.esPrestable = TRUE')
        ->andWhere('(:codigo IS NULL OR a.codigo = :codigo)')
        ->andWhere('(:nombre IS NULL OR a.nombre LIKE :nombre)')
        ->andWhere('(:tipoItem IS NULL OR a.tipoItem = :tipoItem)')
        ->andWhere('(:estanteria IS NULL OR a.estanteria = :estanteria)')
        ->setParameter('codigo', $buscadorDTO->codigo);
        if($buscadorDTO->nombre)
        {
		    $q = $q->setParameter('nombre', '%'.str_replace(' ','%',$buscadorDTO->nombre.'%'));
        }
        else
        {
		    $q = $q->setParameter('nombre', NULL);
        }
        $q = $q->setParameter('tipoItem', $buscadorDTO->tipoItem)
            ->setParameter('estanteria', $buscadorDTO->estanteria);
        return $q->getQuery()->getResult();
    }


  
}

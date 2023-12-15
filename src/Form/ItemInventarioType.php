<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use App\Entity\EstadoItem;
use App\Entity\Item;
use App\Entity\Persona;
use App\Entity\TipoItem;
use App\Entity\Ubicacion;

class ItemInventarioType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('nombre')
			->add('tipoItem',EntityType::class, array(
                'class' => TipoItem::class,
                'placeholder' => '',
                'required' => TRUE,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.esPrestable = FALSE')
                        ->orderBy('a.nombre', 'ASC');
                }
            ))
			->add('ubicacion',EntityType::class, array(
                'class' => Ubicacion::class,
                'placeholder' => '',
                'required' => TRUE,
            ))
            ->add('codigo',IntegerType::class,array(
                'required' => FALSE,
            ))
            ->add('marca',null,array(
                'required' => FALSE,
            ))
            ->add('modelo',null,array(
                'required' => FALSE,
            ))
			->add('observaciones',null,array(
                'required' => FALSE,
            ))
            ->add('fechaIncorporacion',DateType::class,array(
                'required' => FALSE,
                'widget' => 'single_text',
                'html5' => true,
            ))
			->add('estadoItem',EntityType::class, array(
                'class' => EstadoItem::class,
                'placeholder' => '',
                'required' => TRUE,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.esInventario = TRUE')
                        ->orderBy('a.nombre', 'ASC');
                }                
            ))
            ->add('responsable',EntityType::class, array(
                'class' => Persona::class,
                'placeholder' => '',
                'required' => FALSE,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->leftJoin('a.tipoPersona', 'tp')
                        ->where('tp.esResponsable = TRUE')
                        ->orderBy('a.nombres', 'ASC');
                }               
            ))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Item::class,
        ));
    }
}
<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\ORM\EntityRepository;
use App\DTO\InventarioBuscadorDto;
use App\Entity\TipoItem;
use App\Entity\Ubicacion;

class InventarioBuscadorType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('nombre')
			->add('tipoItem',EntityType::class, array(
                'class' => TipoItem::class,
                'placeholder' => '',
                'required' => FALSE,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->where('a.esPrestable = FALSE')
                        ->orderBy('a.nombre', 'ASC');
                }
            ))
			->add('ubicacion',EntityType::class, array(
                'class' => Ubicacion::class,
                'placeholder' => '',
                'required' => FALSE,
            ))
            ->add('codigo',IntegerType::class,array(
                'required' => FALSE,
            ))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => InventarioBuscadorDto::class,
        ));
    }
}
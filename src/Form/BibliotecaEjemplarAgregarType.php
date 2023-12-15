<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use App\DTO\BibliotecaEjemplarAgregarDto;
use App\Entity\EstadoLibro;
use App\Entity\Ubicacion;

class BibliotecaEjemplarAgregarType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
        ->add('copias',IntegerType::class,array(
            'required' => TRUE,
            'attr' => array(
                'autofocus' => true
                )
            )
        )
        ->add('estado',EntityType::class, array(
            'class' => EstadoLibro::class,
            'placeholder' => '',
            'required' => TRUE,
        ))
        ->add('ubicacion',EntityType::class, array(
            'class' => Ubicacion::class,
            'placeholder' => '',
            'required' => TRUE,
        ))
        ->add('codigoInicial',IntegerType::class,array(
            'required' => TRUE,
            'attr' => array(
                'autofocus' => true
                )
            )
        )
		;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BibliotecaEjemplarAgregarDto::class,
        ));
    }
}
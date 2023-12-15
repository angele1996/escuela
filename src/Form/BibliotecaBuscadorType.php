<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\ORM\EntityRepository;
use App\DTO\BibliotecaBuscadorDto;
use App\Entity\Ubicacion;

class BibliotecaBuscadorType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
            ->add('nombre_libro')
            ->add('nombre_autor')
            ->add('isbn')
            ->add('codigo_barra',IntegerType::class,array(
                'required' => FALSE,
            ))
            ->add('ubicacion',EntityType::class, array(
                'class' => Ubicacion::class,
                'placeholder' => '',
                'required' => FALSE,
            ))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BibliotecaBuscadorDto::class,
        ));
    }
}
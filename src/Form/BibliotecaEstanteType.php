<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityRepository;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Libro;
use App\Entity\Ubicacion;
use App\DTO\BibliotecaLibroEstanteDto;


class BibliotecaEstanteType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
        ->add('estante',EntityType::class, array(
            'class' => Ubicacion::class,
            'placeholder' => '',
            'required' => TRUE,
        ))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BibliotecaLibroEstanteDto::class,
        ));
    }
}
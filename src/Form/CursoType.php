<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
use App\Entity\Anio;
use App\Entity\Curso;

class CursoType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
        ->add('anio',EntityType::class, array(
            'class' => Anio::class,
            'placeholder' => '',
            'required' => TRUE,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('a')
                    ->where('a.vigente = TRUE')
                    ->orderBy('a.numero', 'ASC');
            }
        ))
        ->add('nombre')
		;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Curso::class,
        ));
    }
}
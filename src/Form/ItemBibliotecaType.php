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
use App\Entity\EstadoItem;
use App\Entity\Estanteria;
use App\Entity\Item;
use App\Entity\Persona;
use App\Entity\TipoItem;
use App\Entity\Ubicacion;

class ItemBibliotecaType extends AbstractType
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
                        ->where('a.esPrestable = TRUE')
                        ->orderBy('a.nombre', 'ASC');
                }
            ))
			->add('estanteria',EntityType::class, array(
                'class' => Estanteria::class,
                'placeholder' => '',
                'required' => TRUE,
            ))
            ->add('codigo',IntegerType::class,array(
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
                        ->where('a.esBiblioteca = TRUE')
                        ->orderBy('a.nombre', 'ASC');
                }                
            ))
            ->add('isbn')
            ->add('autor')
            ->add('editorial')
            ->add('edad')
            ->add('imageFile', VichImageType::class, array(
                'label' => 'Imagen',
                'required' => false,
                'allow_delete' => true, // not mandatory, default is true
                'download_link' => true, // not mandatory, default is true
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
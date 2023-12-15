<?php

namespace App\Form;

use App\DTO\MatriculaFormularioInicialDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatriculaFormularioInicialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('rut', TextType::class, array(
            'label' => 'RUN del Estudiante', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control input-rut')
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MatriculaFormularioInicialDto::class
        ]);
    }
}

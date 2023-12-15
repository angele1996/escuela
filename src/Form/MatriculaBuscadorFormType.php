<?php

namespace App\Form;

use App\DTO\MatriculaBuscadorDto;
use App\Entity\Curso;
use App\Repository\CursoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MatriculaBuscadorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('rut', TextType::class, array(
            'label' => 'RUN del Estudiante', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control input-rut')
        ))
        ->add('curso', EntityType::class, array(
            'label' => 'Curso', 
            'required' => FALSE, 
            'placeholder' => 'TODOS',
            'class' => Curso::class,
            'query_builder' => function (CursoRepository $er) {
                return $er->queryBuilderCursosMatricula();
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('nombres', TextType::class, array(
            'label' => 'Nombres', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apellidos', TextType::class, array(
            'label' => 'Apellidos', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('matriculaCompletada', ChoiceType::class, array(
            'label' => 'Estado Matricula', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control'),
            'choices'  => array(
                'TODOS' => '',
                'MATRICULADOS' => true,
                'PENDIENTES' => false,
        )))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MatriculaBuscadorDto::class
        ]);
    }
}

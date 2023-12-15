<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use App\Entity\User;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
            ->add('email', EmailType::class)
            ->add('username', TextType::class)
            ->add("justpassword", TextType::class, [
                "label" => 'Contraseña',
                "required" => true,
                "mapped" => false, 
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('permisoAdministrador',ChoiceType::class,array(
                'required'=>TRUE,
                'placeholder' => '',
                'choices'  => array(
                    'SI' => true,
                    'NO' => false,
                ),
                'expanded' => FALSE, 
                'multiple' => FALSE))
            ->add('permisoBiblioteca',ChoiceType::class,array(
                'required'=>TRUE,
                'placeholder' => '',
                'choices'  => array(
                    'SI' => true,
                    'NO' => false,
                ),
                'expanded' => FALSE, 
                'multiple' => FALSE))
            ->add('permisoInventario',ChoiceType::class,array(
                'required'=>TRUE,
                'placeholder' => '',
                'choices'  => array(
                    'SI' => true,
                    'NO' => false,
                ),
                'expanded' => FALSE, 
                'multiple' => FALSE))
            ->add('permisoMatricula',ChoiceType::class,array(
                'required'=>TRUE,
                'placeholder' => '',
                'choices'  => array(
                    'SI' => true,
                    'NO' => false,
                ),
                'expanded' => FALSE, 
                'multiple' => FALSE))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
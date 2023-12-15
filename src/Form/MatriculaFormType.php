<?php

namespace App\Form;

use App\Entity\ConQuienVive;
use App\Entity\Comuna;
use App\Entity\Curso;
use App\Entity\Genero;
use App\Entity\EstadoCivil;
use App\Entity\Nacionalidad;
use App\Entity\NivelEducacional;
use App\Entity\Matricula;
use App\Entity\Parentesco;
use App\Entity\Religion;
use App\Repository\CursoRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MatriculaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('curso', EntityType::class, array(
            'label' => 'Curso', 
            'required' => TRUE, 
            'placeholder' => 'SELECCIONAR',
            'class' => Curso::class,
            'query_builder' => function (CursoRepository $er) {
                return $er->queryBuilderCursosMatricula();
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('rut', TextType::class, array(
            'label' => 'RUN', 
            'required' => FALSE, 
            'attr' => array(
                'class' => 'form-control input-rut', 
                'readonly' => true)
        ))
        ->add('apellidoPaterno', TextType::class, array(
            'label' => 'Apellido Paterno', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apellidoMaterno', TextType::class, array(
            'label' => 'Apellido Materno', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('nombres', TextType::class, array(
            'label' => 'Nombres', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('fechaNacimiento', DateType::class, array(
            'label' => 'Fecha de Nacimiento',
            'required' => TRUE, 
            'widget' => 'single_text',
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'help' => 'Formato: dd/mm/aaaa',
            'attr' => array('class' => 'form-control input-fecha'),
        ))
        ->add('ciudadNacimiento', TextType::class, array(
            'label' => 'Ciudad Nacimiento', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('nacionalidad', EntityType::class, array(
            'label' => 'Pais de Origen', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Nacionalidad::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('domicilio', TextType::class, array(
            'label' => 'Domicilio', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('comuna', EntityType::class, array(
            'label' => 'Comuna', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Comuna::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('genero', EntityType::class, array(
            'label' => 'Género', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Genero::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('telefono', IntegerType::class, array(
            'label' => 'Teléfono', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('correoElectronico', EmailType::class, array(
            'label' => 'Correo Electrónico', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('nombreTelefonoContacto1', TextType::class, array(
            'label' => 'Nombre', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('parentescoTelefonoContacto1', EntityType::class, array(
            'label' => 'Parentesco', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Parentesco::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('numeroTelefonoContacto1', IntegerType::class, array(
            'label' => 'Número', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('nombreTelefonoContacto2', TextType::class, array(
            'label' => 'Nombre', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('parentescoTelefonoContacto2', EntityType::class, array(
            'label' => 'Parentesco', 
            'required' => FALSE, 
            'placeholder' => '',
            'class' => Parentesco::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('numeroTelefonoContacto2', IntegerType::class, array(
            'label' => 'Número', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('nombreTelefonoContacto3', TextType::class, array(
            'label' => 'Nombre', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('parentescoTelefonoContacto3', EntityType::class, array(
            'label' => 'Parentesco', 
            'required' => FALSE, 
            'placeholder' => '',
            'class' => Parentesco::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('numeroTelefonoContacto3', IntegerType::class, array(
            'label' => 'Número', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('colegioProcedencia', TextType::class, array(
            'label' => 'Colegio de Procedencia', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('conQuienVive', EntityType::class, array(
            'label' => '¿Con quien vive el niño?', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => ConQuienVive::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('repiteCurso', ChoiceType::class, array(
            'label' => '¿Repite curso?', 
            'required' => TRUE, 
            'choices' => array('SI' => true, 'NO' => false),
            'attr' => array('class' => 'form-control')
        ))
        ->add('necesidadesEducativasEspeciales', ChoiceType::class, array(
            'label' => '¿Presenta Necesidades Educativas Especiales?', 
            'required' => TRUE, 
            'choices' => array('SI' => true, 'NO' => false),
            'attr' => array('class' => 'form-control')
        ))
        ->add('necesidadesEducativasEspecialesCual', TextType::class, array(
            'label' => '¿Cuál?', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreRut', TextType::class, array(
            'label' => 'RUN', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control input-rut')
        ))
        ->add('padreNombre', TextType::class, array(
            'label' => 'Nombre Completo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreTelefono', IntegerType::class, array(
            'label' => 'Teléfono', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreCorreoElectronico', EmailType::class, array(
            'label' => 'Correo Electrónico', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreNivelEducacional', EntityType::class, array(
            'label' => 'Nivel Educacional', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => NivelEducacional::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreProfesion', TextType::class, array(
            'label' => 'Profesión', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreDireccion', TextType::class, array(
            'label' => 'Dirección', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreLugarTrabajo', TextType::class, array(
            'label' => 'Lugar de Trabajo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padreDireccionTrabajo', TextType::class, array(
            'label' => 'Dirección de Trabajo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreRut', TextType::class, array(
            'label' => 'RUN', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control input-rut')
        ))
        ->add('madreNombre', TextType::class, array(
            'label' => 'Nombre Completo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreTelefono', IntegerType::class, array(
            'label' => 'Teléfono', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreCorreoElectronico', EmailType::class, array(
            'label' => 'Correo Electrónico', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreNivelEducacional', EntityType::class, array(
            'label' => 'Nivel Educacional', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => NivelEducacional::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreProfesion', TextType::class, array(
            'label' => 'Profesión', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreDireccion', TextType::class, array(
            'label' => 'Dirección', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreLugarTrabajo', TextType::class, array(
            'label' => 'Lugar de Trabajo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('madreDireccionTrabajo', TextType::class, array(
            'label' => 'Dirección de Trabajo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoParentesco', EntityType::class, array(
            'label' => 'Vínculo que lo une al alumno(a)', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Parentesco::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoViveConEstudiante', ChoiceType::class, array(
            'label' => '¿Vive con el niño?', 
            'required' => TRUE, 
            'choices' => array('SI' => true, 'NO' => false),
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoRut', TextType::class, array(
            'label' => 'RUN', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control input-rut')
        ))
        ->add('apoderadoNombre', TextType::class, array(
            'label' => 'Nombre Completo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoTelefono', IntegerType::class, array(
            'label' => 'Teléfono', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoCorreoElectronico', EmailType::class, array(
            'label' => 'Correo Electrónico', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoGenero', EntityType::class, array(
            'label' => 'Género', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Genero::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoEstadoCivil', EntityType::class, array(
            'label' => 'Estado Civil', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => EstadoCivil::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoNivelEducacional', EntityType::class, array(
            'label' => 'Nivel Educacional', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => NivelEducacional::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoProfesion', TextType::class, array(
            'label' => 'Profesión', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoDireccion', TextType::class, array(
            'label' => 'Dirección', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoLugarTrabajo', TextType::class, array(
            'label' => 'Lugar de Trabajo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('apoderadoDireccionTrabajo', TextType::class, array(
            'label' => 'Dirección de Trabajo', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('padresProfesanReligion', ChoiceType::class, array(
            'label' => '¿Los padres o el apoderado profesan alguna Religión? ', 
            'required' => TRUE, 
            'choices' => array('SI' => true, 'NO' => false),
            'attr' => array('class' => 'form-control')
        ))
        ->add('padresSeleccionReligion', EntityType::class, array(
            'label' => 'Seleccionar', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Religion::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('padresReligion', TextType::class, array(
            'label' => '¿Cuál?', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('quienRetiraParentesco', EntityType::class, array(
            'label' => '¿Quién retira a su hijo del Establecimiento?', 
            'required' => TRUE, 
            'placeholder' => '',
            'class' => Parentesco::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.nombre', 'ASC');
            },
            'attr' => array('class' => 'form-control')
        ))
        ->add('quienRetiraNombre', TextType::class, array(
            'label' => 'Indique nombre y apellido', 
            'required' => TRUE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('observaciones', TextareaType::class, array(
            'label' => 'Observaciones', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('clinicaTieneSeguro', ChoiceType::class, array(
            'label' => '¿El estudiante tiene seguro de accidente en alguna institución particular?', 
            'required' => TRUE, 
            'choices' => array('SI' => true, 'NO' => false),
            'attr' => array('class' => 'form-control')
        ))
        ->add('clinicaInstitucionSeguro', TextType::class, array(
            'label' => 'Nombre de la institución donde tiene seguro ', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('clinicaTelefonoInstitucionSeguro', IntegerType::class, array(
            'label' => 'Registre teléfono de la institución en caso de emergencia', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('clinicaTieneEnfermedadCuidadoEspecial', ChoiceType::class, array(
            'label' => '¿El estudiante padece alguna enfermedad de cuidado especial?', 
            'required' => TRUE, 
            'choices' => array('SI' => true, 'NO' => false),
            'attr' => array('class' => 'form-control')
        ))
        ->add('clinicaEnfermedadCuidadoEspecial', TextType::class, array(
            'label' => '¿Cuál?', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('clinicaRecomendaciones', TextareaType::class, array(
            'label' => 'Si padece  alguna enfermedad de cuidado especial, indique recomendaciones al Colegio', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('clinicaObservaciones', TextareaType::class, array(
            'label' => 'Observaciones', 
            'required' => FALSE, 
            'attr' => array('class' => 'form-control')
        ))
        ->add('fotoApoderadoFile', VichImageType::class, array(
            'label' => 'Foto Apoderado (PNG o JPG)', 
            'required' => FALSE,
            'allow_delete' => FALSE,
            'delete_label' => 'Eliminar',
            'download_label' => 'Descargar',
            'download_uri' => TRUE,
            'image_uri' => TRUE,
            'imagine_pattern' => 'mini',
            'asset_helper' => FALSE,
            'attr' => array('accept' => 'image/png, image/jpeg'),
        ))
        ->add('fotoEstudianteFile', VichImageType::class, array(
            'label' => 'Foto Estudiante (PNG o JPG)', 
            'required' => FALSE,
            'allow_delete' => FALSE,
            'delete_label' => 'Eliminar',
            'download_label' => 'Descargar',
            'download_uri' => TRUE,
            'image_uri' => TRUE,
            'imagine_pattern' => 'mini',
            'asset_helper' => FALSE,
            'attr' => array('accept' => 'image/png, image/jpeg'),
        ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matricula::class
        ]);
    }
}

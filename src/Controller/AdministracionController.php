<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\AlumnoCargaMasivaDto;
use App\Entity\Alumno;
use App\Entity\Anio;
use App\Entity\Curso;
use App\Entity\Persona;
use App\Entity\TipoPersona;
use App\Entity\User;
use App\Form\AnioType;
use App\Form\AlumnoCargaMasivaType;
use App\Form\CursoType;
use App\Form\PersonaType;
use App\Form\UserType;
use App\Form\UserEditarType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;

class AdministracionController extends AbstractController
{
    private $entityManager;
    private $passwordEncoder;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/admin/usuarios/", name="admin-usuarios")
     */
    public function usuarios(Request $request)
    {
        try
        {
            $repositoryUser = $this->getDoctrine()->getRepository(User::class);

            $lista = $repositoryUser->findAll();

            return $this->render('administracion/usuarios.html.twig',
                array(
                    'lista' => $lista,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }
    
    /**
     * @Route("/admin/usuarios/agregar/", name="admin-usuarios-agregar")
     */
    public function usuariosAgregar(Request $request)
    {
        try
        {
            $form = $this->createForm(UserType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $item = $form->getData();
                $password = $form["justpassword"]->getData();
                $item->setPassword($this->passwordEncoder->encodePassword($item, $password))
                    ->setRoles(["ROLE_USER"]);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
                $this->addFlash('success','Se ha agregado el usuario');
                return $this->redirectToRoute('admin-usuarios');
            }

            return $this->render('administracion/usuarios-agregar.html.twig',
                array(
                    'form' => $form->createView(),
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/admin/usuarios/{user}/editar/", name="admin-usuarios-editar")
     */
    public function usuariosEditar(Request $request, User $user)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $form = $this->createForm(UserEditarType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success','Se ha modificado los datos del usuario');
                return $this->redirectToRoute('admin-usuarios');
            }

            return $this->render('administracion/usuarios-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'usuario' => $user,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/admin/usuarios/{user}/password/", name="admin-usuarios-password")
     */
    public function usuariosPassword(Request $request, User $user)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $form = $this->createForm(UserPasswordType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $password = $form["justpassword"]->getData();
                $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
                $this->entityManager->persist($user);
                
                $entityManager->flush();
                $this->addFlash('success','Se ha cambiado contraseña del usuario');
                return $this->redirectToRoute('admin-usuarios');
            }

            return $this->render('administracion/usuarios-password.html.twig',
                array(
                    'form' => $form->createView(),
                    'usuario' => $user,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

}

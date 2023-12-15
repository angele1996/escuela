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

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        try
        {
            if($this->getUser())
            {
                return $this->redirectToRoute('main_index');
            }
            else
            {
                return $this->redirectToRoute('matricula_index');
            }
        }
        catch(\Exception $e)
        {
            error_log($e->getMessage());
            $this->addFlash('danger','Error: '.$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/", name="main_index")
     */
    public function main()
    {
        try
        {
            return $this->render('main/index.html.twig');
        }
        catch(\Exception $e)
        {
            error_log($e->getMessage());
            $this->addFlash('danger','Error: '.$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/anios/", name="main-anios")
     */
    public function anios(Request $request)
    {
        try
        {
            $repositoryAnio = $this->getDoctrine()->getRepository(Anio::class);

            $lista = $repositoryAnio->findAll();

            return $this->render('main/anios.html.twig',
                array(
                    'lista' => $lista,
                )
            );
        }
        catch(\Exception $e)
        {
            error_log($e->getMessage());
            $this->addFlash('danger','Error: '.$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/anios/agregar/", name="main-anios-agregar")
     */
    public function aniosAgregar(Request $request)
    {
        try{
            $entityManager = $this->getDoctrine()->getManager();
        
            $anio = new Anio();
            $form = $this->createForm(AnioType::class, $anio);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $entityManager->persist($anio);
                $entityManager->flush();
                $this->addFlash('success','Se ha agregado el año académico');
                return $this->redirectToRoute('main-anios');
            }

            return $this->render('main/anios-agregar.html.twig',
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
     * @Route("/main/anios/{anio}/editar/", name="main-anios-editar")
     */
    public function aniosEditar(Request $request, Anio $anio)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $form = $this->createForm(AnioType::class, $anio);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $entityManager->persist($anio);
                $entityManager->flush();
                $this->addFlash('success','Se ha modificado los datos del año académico');
                return $this->redirectToRoute('main-anios');
            }

            return $this->render('main/anios-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'anio' => $anio,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/anios/{anio}/eliminar/", name="main-anios-eliminar")
     */
    public function aniosEliminar(Request $request, Anio $anio)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $entityManager->remove($anio);
            $entityManager->flush();

            $this->addFlash('success','Se han eliminado los datos del año académico');
            return $this->redirectToRoute('main-anios');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/cursos/", name="main-cursos")
     */
    public function cursos(Request $request)
    {
        try
        {
            $repositoryCurso = $this->getDoctrine()->getRepository(Curso::class);

            $lista = $repositoryCurso->findCursosVigentes();

            return $this->render('main/cursos.html.twig',
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
     * @Route("/main/cursos/agregar/", name="main-cursos-agregar")
     */
    public function cursosAgregar(Request $request)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $curso = new Curso();
            $form = $this->createForm(CursoType::class, $curso);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $entityManager->persist($curso);
                $entityManager->flush();
                $this->addFlash('success','Se ha agregado el curso');
                return $this->redirectToRoute('main-cursos');
            }

            return $this->render('main/cursos-agregar.html.twig',
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
     * @Route("/main/cursos/{curso}/editar/", name="main-cursos-editar")
     */
    public function cursosEditar(Request $request, Curso $curso)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $form = $this->createForm(CursoType::class, $curso);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $entityManager->persist($curso);
                $entityManager->flush();
                $this->addFlash('success','Se ha modificado los datos del curso');
                return $this->redirectToRoute('main-cursos');
            }

            return $this->render('main/cursos-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'curso' => $curso,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/cursos/{curso}/eliminar/", name="main-cursos-eliminar")
     */
    public function cursosEliminar(Request $request, Curso $curso)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $entityManager->remove($curso);
            $entityManager->flush();

            $this->addFlash('success','Se han eliminado los datos del curso');
            return $this->redirectToRoute('main-cursos');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    

    /**
     * @Route("/main/profesores/", name="main-profesores")
     */
    public function profesores(Request $request)
    {
        try
        {
            $repositoryPersona = $this->getDoctrine()->getRepository(Persona::class);

            $lista = $repositoryPersona->findProfesores();

            return $this->render('main/profesores.html.twig',
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
     * @Route("/main/profesores/agregar/", name="main-profesores-agregar")
     */
    public function profesoresAgregar(Request $request)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryTipoPersona = $this->getDoctrine()->getRepository(TipoPersona::class);

            $tipoPersona = $repositoryTipoPersona->findOneByEsResponsable(TRUE);
            if(!$tipoPersona)
            {
                $this->addFlash('danger','No se encuentra tipo de persona');
                return $this->redirectToRoute('main-profesores');
            }

            $persona = new Persona();
            $form = $this->createForm(PersonaType::class, $persona);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $persona->setTipoPersona($tipoPersona);
                $entityManager->persist($persona);
                $entityManager->flush();
                $this->addFlash('success','Se ha agregado el profesor');
                return $this->redirectToRoute('main-profesores');
            }

            return $this->render('main/profesores-agregar.html.twig',
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
     * @Route("/main/profesores/{persona}/editar/", name="main-profesores-editar")
     */
    public function profesoresEditar(Request $request, Persona $persona)
    {
        try 
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $form = $this->createForm(PersonaType::class, $persona);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $entityManager->persist($persona);
                $entityManager->flush();
                $this->addFlash('success','Se ha modificado los datos del profesor');
                return $this->redirectToRoute('main-profesores');
            }

            return $this->render('main/profesores-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'persona' => $persona,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/profesores/{persona}/eliminar/", name="main-profesores-eliminar")
     */
    public function profesoresEliminar(Request $request, Persona $persona)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $entityManager->remove($persona);
            $entityManager->flush();

            $this->addFlash('success','Se han eliminado los datos del profesor');
            return $this->redirectToRoute('main-profesores');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/alumnos/", name="main-alumnos")
     */
    public function alumnos(Request $request)
    {
        try
        {
            $repositoryCurso = $this->getDoctrine()->getRepository(Curso::class);

            $listaCursos = $repositoryCurso->findCursosVigentes();

            return $this->render('main/alumnos.html.twig',
                array(
                    'listaCursos' => $listaCursos,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/alumnos/{curso}/agregar/", name="main-alumnos-agregar")
     */
    public function alumnosAgregar(Request $request, Curso $curso)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryTipoPersona = $this->getDoctrine()->getRepository(TipoPersona::class);
            $repositoryPersona = $this->getDoctrine()->getRepository(Persona::class);

            $tipoPersona = $repositoryTipoPersona->findOneByEsResponsable(FALSE);
            if(!$tipoPersona)
            {
                $this->addFlash('danger','No se encuentra tipo de persona');
                return $this->redirectToRoute('main-alumnos');
            }

            $persona = new Persona();
            $form = $this->createForm(PersonaType::class, $persona);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $credencial = $persona->getCredencial();
                if($credencial == 0)
                {
                    $objCredencial = $repositoryPersona->getUltimaCredencial();
                    if(!$objCredencial)
                    {
                        $credencial = 1000;
                    }
                    $credencial = $objCredencial['credencial'] + 1;
                    $persona->setCredencial($credencial);
                }

                $alumno = new Alumno();
                $alumno->setCurso($curso);
                
                $persona->setTipoPersona($tipoPersona);
                $persona->addAlumno($alumno);

                $entityManager->persist($alumno);
                $entityManager->persist($persona);
                
                $entityManager->flush();
                $this->addFlash('success','Se ha agregado el alumno');
                return $this->redirectToRoute('main-alumnos-curso', array('curso' => $curso->getId()));
            }

            return $this->render('main/alumnos-agregar.html.twig',
                array(
                    'form' => $form->createView(),
                    'curso' => $curso,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/alumnos/{curso}/carga-masiva/", name="main-alumnos-carga-masiva")
     */
    public function alumnosCargaMasiva(Request $request, Curso $curso)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
            $repositoryTipoPersona = $this->getDoctrine()->getRepository(TipoPersona::class);
            $repositoryPersona = $this->getDoctrine()->getRepository(Persona::class);

            $tipoPersona = $repositoryTipoPersona->findOneByEsResponsable(FALSE);
            if(!$tipoPersona)
            {
                $this->addFlash('danger','No se encuentra tipo de persona');
                return $this->redirectToRoute('main-alumnos-curso', array('curso' => $curso->getId()));
            }

            $alumnosDTO = new AlumnoCargaMasivaDto();
            $alumnosDTO->credencialInicial = 0;
            $form = $this->createForm(AlumnoCargaMasivaType::class, $alumnosDTO);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $lista_alumnos = explode("\n", $alumnosDTO->alumnos);
                if(!$lista_alumnos)
                {
                    $this->addFlash('danger','Debe ingresar listado de alumnos');
                    return $this->redirectToRoute('main-alumnos-carga-masiva', array('curso' => $curso->getId()));
                }

                $total = count($lista_alumnos);

                $credencial = $alumnosDTO->credencialInicial;
                if($credencial == 0)
                {
                    $objCredencial = $repositoryPersona->getUltimaCredencial();
                    if(!$objCredencial)
                    {
                        $credencial = 1000;
                    }
                    $credencial = $objCredencial['credencial'] + 1;
                }

                foreach($lista_alumnos as $item_alumno)
                {
                    if(trim($item_alumno))
                    {
                        $nombres = '';
                        $apellidos = '';
                        $pos = strpos($item_alumno, ',');
                        if($pos)
                        {
                            $apellidos = trim(substr($item_alumno, 0, $pos));
                            $nombres = trim(substr($item_alumno, $pos + 1));
                        }
                        else
                        {
                            $nombres = trim($item_alumno);
                        }

                        $alumno = new Alumno();
                        $alumno->setCurso($curso);

                        $persona = new Persona();
                        $persona->setNombres($nombres);
                        $persona->setApellidos($apellidos);
                        $persona->setCredencial($credencial);
                        $persona->setTipoPersona($tipoPersona);
                        $persona->addAlumno($alumno);

                        $entityManager->persist($alumno);
                        $entityManager->persist($persona);

                        $credencial = $credencial + 1;
                    }
                }

                $entityManager->flush();
                $this->addFlash('success','Se han agregado los alumnos');
                return $this->redirectToRoute('main-alumnos-curso', array('curso' => $curso->getId()));
                
            }

            return $this->render('main/alumnos-carga-masiva.html.twig',
                array(
                    'form' => $form->createView(),
                    'curso' => $curso,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/alumnos/{curso}/{persona}/editar/", name="main-alumnos-editar")
     */
    public function alumnosEditar(Request $request, Curso $curso, Persona $persona)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $form = $this->createForm(PersonaType::class, $persona);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $entityManager->persist($persona);
                $entityManager->flush();
                $this->addFlash('success','Se ha modificado los datos del alumno');
                return $this->redirectToRoute('main-alumnos-curso', array('curso' => $curso->getId()));
            }

            return $this->render('main/alumnos-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'persona' => $persona,
                    'curso' => $curso,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/alumnos/{curso}/{persona}/eliminar/", name="main-alumnos-eliminar")
     */
    public function alumnosEliminar(Request $request, Curso $curso, Persona $persona)
    {
        try
        {
            $entityManager = $this->getDoctrine()->getManager();
        
            $lista_alumnos = $persona->getAlumnos();
            foreach($lista_alumnos as $item_alumno)
            {
                $entityManager->remove($item_alumno);
            }
            $entityManager->remove($persona);
            $entityManager->flush();

            $this->addFlash('success','Se han eliminado los datos del alumno');
            return $this->redirectToRoute('main-alumnos-curso', array('curso' => $curso->getId()));
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }
    
    /**
     * @Route("/main/alumnos/curso/{curso}/", name="main-alumnos-curso")
     */
    public function alumnosCurso(Request $request, Curso $curso)
    {
        try
        {
            $repositoryCurso = $this->getDoctrine()->getRepository(Curso::class);
            $repositoryPersona = $this->getDoctrine()->getRepository(Persona::class);

            $listaCursos = $repositoryCurso->findCursosVigentes();
            $listaAlumnos = $repositoryPersona->findAlumnosByCurso($curso);

            return $this->render('main/alumnos-curso.html.twig',
                array(
                    'curso' => $curso,
                    'listaCursos' => $listaCursos,
                    'listaAlumnos' => $listaAlumnos,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/alumnos/curso/{curso}/credenciales/", name="main-alumnos-curso-credenciales")
     */
    public function alumnosCursoCredenciales(Request $request, TCPDFController $pdfController, Curso $curso)
    {
        try
        {
            $repositoryPersona = $this->getDoctrine()->getRepository(Persona::class);

            $lista_alumnos = $repositoryPersona->findAlumnosByCurso($curso);

            // create new PDF document
            $ancho_credencial = 90;
            $alto_credencial = 60;
            $ancho_hoja = 216;
            $alto_hoja = 279;
            $margen_superior = 5;
            $margen_izquierdo = 5;
            $margen_derecho = 5;
            $espacio = 5;

            $pdf = $pdfController->create(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $pdf->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho); //(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(0);   
            $pdf->SetAutoPageBreak(TRUE, 0);
            $font_size = $pdf->pixelsToUnits('22');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );

            $page_format = array(
                'MediaBox' => array ('llx' => 0, 'lly' => 0, 'urx' => $ancho_hoja, 'ury' => $alto_hoja),
                'Dur' => 3,
                'trans' => array(
                    'D' => 1.5,
                    'S' => 'Split',
                    'Dm' => 'V',
                    'M' => 'O'
                ),
                'Rotate' => 0,
                'PZ' => 1,
            );

            $style = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => false,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0,0,0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 4
            );

            /*
                NOMBRE
                CURSO
                AÑO

                FOTOGRAFIA
                CODIGO
            */

            
            $columna = 1;
            $fila = 1;
            $pdf->AddPage('P', $page_format, false, false);
            foreach($lista_alumnos as $item_alumno)
            {
                $x1 = $margen_izquierdo + (($columna - 1) * ($ancho_credencial + $espacio));
                $x2 = $x1 + 20;
                $x3 = $x1 + 20;
                $x4 = $x1 + 60;
                $xf = $x1 + $ancho_credencial;

                $y1 = $margen_superior + (($fila - 1) * ($alto_credencial + $espacio));
                $y2 = $y1 + 1;
                $y3 = $y1 + 6   ;
                $y4 = $y1 + 15;
                $y5 = $y1 + 25;
                $y6 = $y1 + 30;
                $y7 = $y1 + 35;
                $y8 = $y1 + 45;
                $yf = $y1 + $alto_credencial;

                $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(42, 81, 150)));

                //RoundedRect(x, y, w, h, r, round_corner = '1111', style = '', border_style = nil, fill_color = nil) ⇒ Object
                //https://www.rubydoc.info/gems/rfpdf/1.17.4/TCPDF:RoundedRect
                $pdf->RoundedRect($x1, $y1, $ancho_credencial, $alto_credencial, 1.50, '1111', 'DF', '', array(255,255,235));


                $pdf->image('logo_hoover.jpg', $x1 + 1, $y2, $x2 - $x1, $y5 - $y2 - 5, 'JPG', '', '', false, 300, '', false, false, 0, null, false, false);
    
                $pdf->writeHTMLCell($xf - $x2, $y3 - $y2, $x2, $y2, '<b>CREDENCIAL BIBLIOTECA</b>', 0, 0, false, true, 'C', true);    
                $pdf->writeHTMLCell($xf - $x2, $y4 - $y3, $x2, $y3, '<b>COLEGIO DR. WILLIS HOOVER LA CISTERNA</b>', 0, 0, false, true, 'C', true);    
                $pdf->writeHTMLCell($x3 - $x1, $y6 - $y5, $x1, $y5, '<b>NOMBRE</b>', 0, 0, false, true, '', true);    
                $pdf->writeHTMLCell($x3 - $x1, $y7 - $y6, $x1, $y6, '<b>CURSO</b>', 0, 0, false, true, '', true);    
                $pdf->writeHTMLCell($x3 - $x1, $y8 - $y7, $x1, $y7, '<b>AÑO</b>', 0, 0, false, true, '', true);    
                $pdf->writeHTMLCell($x4 - $x3, $y6 - $y5, $x3, $y5, $item_alumno->getNombres().' '.$item_alumno->getApellidos(), 0, 0, false, true, '', true);    
                $pdf->writeHTMLCell($x4 - $x3, $y7 - $y6, $x3, $y6, $curso->getNombre(), 0, 0, false, true, '', true);    
                $pdf->writeHTMLCell($x4 - $x3, $y8 - $y7, $x3, $y7, $curso->getAnio()->getNumero(), 0, 0, false, true, '', true);    
        
                $pdf->write1DBarcode($item_alumno->getCredencial(), 'C128', $x4, $y8, $xf - $x4 - 2, $yf - $y8, 0.4, $style, 'N');

                if($item_alumno->getImage())
                {
                    $pdf->image('imagenes/personas/'.$item_alumno->getImage(), $x4, $y4, $xf - $x4 - 2, $y8 - $y4, 'JPG', '', '', false, 300, '', false, false, 0, null, false, false);
                }

                $columna = $columna + 1;
                $x1 = $margen_izquierdo + (($columna) * ($ancho_credencial + $espacio));
                if($x1 > $ancho_hoja)
                {
                    $columna = 1;
                    $fila = $fila + 1;

                    $y1 = $margen_superior + (($fila) * ($alto_credencial + $espacio));
                    if($y1 > $alto_hoja)
                    {
                        $fila = 1;
                        $pdf->AddPage('P', $page_format, false, false);
                    }
                }
                
            }

            $pdf->Output("Credenciales.pdf",'I'); // This will output the PDF as a response directly
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }
}

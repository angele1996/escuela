<?php

namespace App\Controller;

use App\DTO\MatriculaBuscadorDto;
use App\DTO\MatriculaFormularioInicialDto;
use App\Entity\Matricula;
use App\Entity\Napsis;
use App\Form\MatriculaFormType;
use App\Form\MatriculaBuscadorFormType;
use App\Form\MatriculaFormularioInicialType;
use App\Repository\MatriculaRepository;
use App\Repository\NapsisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class MatriculaController extends AbstractController
{
    private $entityManager;
    private $session;
    private $mailer;
    private $kernel;
    private $pdfController;
    private $imagineCacheManager;
    private $twig;
    private $matriculaRepository;
    private $napsisRepository;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session, 
        \Swift_Mailer $mailer, KernelInterface $kernel, TCPDFController $pdfController, Environment $twig,
        CacheManager $liipCache,
        MatriculaRepository $matriculaRepository, NapsisRepository $napsisRepository)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->kernel = $kernel;
        $this->pdfController = $pdfController;
        $this->imagineCacheManager = $liipCache;
        $this->twig = $twig;
        $this->matriculaRepository = $matriculaRepository;
        $this->napsisRepository = $napsisRepository;
    }

    /**
     * @Route("/matricula/", name="matricula_index")
     */
    public function index(Request $request): Response
    {
        try{
            $this->session->set('SESION_MATRICULA_RUT', '');
            $this->session->set('SESION_MATRICULA_ID', '');
            return $this->render("matricula/index_2022.html.twig");
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/matricula/apoderado/", name="matricula_apoderado")
     */
    public function apoderado(Request $request, UploaderHelper $helper): Response
    {
        try{
            $form = $this->createForm(MatriculaFormularioInicialType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $item = $form->getData();
                $rut = str_replace(' ','',str_replace('.','',str_replace('-','',$item->rut)));
                $rut = substr($rut,0,strlen($rut)-1).'-'.substr($rut,strlen($rut)-1,1);
                $this->session->set('SESION_MATRICULA_RUT', $rut);
                return $this->redirectToRoute("matricula_apoderado_ficha");
            }
            return $this->render("matricula/apoderado.html.twig", ["form" => $form->createView()]);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/matricula/apoderado/ficha/", name="matricula_apoderado_ficha")
     */
    public function apoderadoFicha(Request $request, UploaderHelper $helper): Response
    {
        try{
            $ahora = new \DateTime('now');

            $rut = $this->session->get('SESION_MATRICULA_RUT');
            if(!$rut)
            {
                return $this->redirectToRoute("matricula_index");
            }

            $item = $this->matriculaRepository->findEnMatriculaActual($rut);
            if(!$item)
            {
                $item = new Matricula();
                $item->setFechaRegistro($ahora);
                $item->setRut($rut);
                $item->setActivo(TRUE);

                $item_anterior = $this->matriculaRepository->findEnMatriculaAnterior($rut);
                if($item_anterior)
                {
                    $item->setNacionalidad($item_anterior->getNacionalidad());
                    $item->setComuna($item_anterior->getComuna());
                    $item->setConQuienVive($item_anterior->getConQuienVive());
                    $item->setPadreNivelEducacional($item_anterior->getPadreNivelEducacional());
                    $item->setMadreNivelEducacional($item_anterior->getMadreNivelEducacional());
                    $item->setApoderadoParentesco($item_anterior->getApoderadoParentesco());
                    $item->setApoderadoNivelEducacional($item_anterior->getApoderadoNivelEducacional());
                    $item->setQuienRetiraParentesco($item_anterior->getQuienRetiraParentesco());
                    $item->setApellidoPaterno($item_anterior->getApellidoPaterno());
                    $item->setApellidoMaterno($item_anterior->getApellidoMaterno());
                    $item->setNombres($item_anterior->getNombres());
                    $item->setFechaNacimiento($item_anterior->getFechaNacimiento());
                    $item->setCiudadNacimiento($item_anterior->getCiudadNacimiento());
                    $item->setDomicilio($item_anterior->getDomicilio());
                    $item->setNombreTelefonoContacto1($item_anterior->getNombreTelefonoContacto1());
                    $item->setNumeroTelefonoContacto1($item_anterior->getNumeroTelefonoContacto1());
                    $item->setNombreTelefonoContacto2($item_anterior->getNombreTelefonoContacto2());
                    $item->setNumeroTelefonoContacto2($item_anterior->getNumeroTelefonoContacto2());
                    $item->setNombreTelefonoContacto3($item_anterior->getNombreTelefonoContacto3());
                    $item->setNumeroTelefonoContacto3($item_anterior->getNumeroTelefonoContacto3());
                    $item->setColegioProcedencia($item_anterior->getColegioProcedencia());
                    $item->setRepiteCurso($item_anterior->getRepiteCurso());
                    $item->setNecesidadesEducativasEspeciales($item_anterior->getNecesidadesEducativasEspeciales());
                    $item->setPadreNombre($item_anterior->getPadreNombre());
                    $item->setPadreCorreoElectronico($item_anterior->getPadreCorreoElectronico());
                    $item->setPadreProfesion($item_anterior->getPadreProfesion());
                    $item->setPadreLugarTrabajo($item_anterior->getPadreLugarTrabajo());
                    $item->setPadreDireccionTrabajo($item_anterior->getPadreDireccionTrabajo());
                    $item->setMadreNombre($item_anterior->getMadreNombre());
                    $item->setMadreCorreoElectronico($item_anterior->getMadreCorreoElectronico());
                    $item->setMadreProfesion($item_anterior->getMadreProfesion());
                    $item->setMadreLugarTrabajo($item_anterior->getMadreLugarTrabajo());
                    $item->setMadreDireccionTrabajo($item_anterior->getMadreDireccionTrabajo());
                    $item->setApoderadoEsPadre($item_anterior->getApoderadoEsPadre());
                    $item->setApoderadoEsMadre($item_anterior->getApoderadoEsMadre());
                    $item->setApoderadoViveConEstudiante($item_anterior->getApoderadoViveConEstudiante());
                    $item->setApoderadoNombre($item_anterior->getApoderadoNombre());
                    $item->setApoderadoCorreoElectronico($item_anterior->getApoderadoCorreoElectronico());
                    $item->setApoderadoProfesion($item_anterior->getApoderadoProfesion());
                    $item->setApoderadoLugarTrabajo($item_anterior->getApoderadoLugarTrabajo());
                    $item->setApoderadoDireccionTrabajo($item_anterior->getApoderadoDireccionTrabajo());
                    $item->setPadresProfesanReligion($item_anterior->getPadresProfesanReligion());
                    $item->setPadresReligion($item_anterior->getPadresReligion());
                    $item->setQuienRetiraNombre($item_anterior->getQuienRetiraNombre());
                    $item->setObservaciones($item_anterior->getObservaciones());
                    $item->setClinicaTieneSeguro($item_anterior->getClinicaTieneSeguro());
                    $item->setClinicaInstitucionSeguro($item_anterior->getClinicaInstitucionSeguro());
                    $item->setClinicaTelefonoInstitucionSeguro($item_anterior->getClinicaTelefonoInstitucionSeguro());
                    $item->setClinicaTieneEnfermedadCuidadoEspecial($item_anterior->getClinicaTieneEnfermedadCuidadoEspecial());
                    $item->setClinicaEnfermedadCuidadoEspecial($item_anterior->getClinicaEnfermedadCuidadoEspecial());
                    $item->setClinicaRecomendaciones($item_anterior->getClinicaRecomendaciones());
                    $item->setClinicaObservaciones($item_anterior->getClinicaObservaciones());
                    $item->setGenero($item_anterior->getGenero());
                    $item->setApoderadoGenero($item_anterior->getApoderadoGenero());
                    $item->setApoderadoEstadoCivil($item_anterior->getApoderadoEstadoCivil());
                    $item->setTelefono($item_anterior->getTelefono());
                    $item->setCorreoElectronico($item_anterior->getCorreoElectronico());
                    $item->setApoderadoTelefono($item_anterior->getApoderadoTelefono());
                    $item->setApoderadoFechaNacimiento($item_anterior->getApoderadoFechaNacimiento());
                    $item->setPadreDireccion($item_anterior->getPadreDireccion());
                    $item->setMadreDireccion($item_anterior->getMadreDireccion());
                    $item->setPadreTelefono($item_anterior->getPadreTelefono());
                    $item->setMadreTelefono($item_anterior->getMadreTelefono());
                    $item->setApoderadoDireccion($item_anterior->getApoderadoDireccion());
                    $item->setPadreRut($item_anterior->getPadreRut());
                    $item->setMadreRut($item_anterior->getMadreRut());
                    $item->setApoderadoRut($item_anterior->getApoderadoRut());
                    $item->setParentescoTelefonoContacto1($item_anterior->getParentescoTelefonoContacto1());
                    $item->setParentescoTelefonoContacto2($item_anterior->getParentescoTelefonoContacto2());
                    $item->setParentescoTelefonoContacto3($item_anterior->getParentescoTelefonoContacto3());
                }
                else
                {
                    $napsis = $this->napsisRepository->findOneByRut($rut);
                    if($napsis)
                    {
                        $item->setNacionalidad($napsis->getNacionalidad());
                        $item->setComuna($napsis->getComuna());
                        $item->setPadreNivelEducacional($napsis->getPadreNivelEducacional());
                        $item->setMadreNivelEducacional($napsis->getMadreNivelEducacional());
                        $item->setApoderadoParentesco($napsis->getApoderadoParentesco());
                        $item->setApoderadoNivelEducacional($napsis->getApoderadoNivelEducacional());
                        $item->setGenero($napsis->getGenero());
                        $item->setApoderadoGenero($napsis->getApoderadoGenero());
                        $item->setApoderadoEstadoCivil($napsis->getApoderadoEstadoCivil());
                        $item->setApellidoPaterno($napsis->getApellidoPaterno());
                        $item->setApellidoMaterno($napsis->getApellidoMaterno());
                        $item->setNombres($napsis->getNombres());
                        $item->setFechaNacimiento($napsis->getFechaNacimiento());
                        $item->setDomicilio($napsis->getDomicilio());
                        $item->setNumeroTelefonoContacto1($napsis->getNumeroTelefonoContacto1());
                        $item->setPadreNombre($napsis->getPadreNombre());
                        $item->setMadreNombre($napsis->getMadreNombre());
                        $item->setApoderadoNombre($napsis->getApoderadoNombre());
                        $item->setApoderadoCorreoElectronico($napsis->getApoderadoCorreoElectronico());
                        $item->setApoderadoProfesion($napsis->getApoderadoProfesion());
                        $item->setPadresProfesanReligion($napsis->getPadresProfesanReligion());
                        $item->setPadresReligion($napsis->getPadresReligion());
                        $item->setCorreoElectronico($napsis->getCorreoElectronico());
                        $item->setApoderadoTelefono($napsis->getApoderadoTelefono());
                        $item->setApoderadoFechaNacimiento($napsis->getApoderadoFechaNacimiento());
                        $item->setPadreDireccion($napsis->getPadreDireccion());
                        $item->setMadreDireccion($napsis->getMadreDireccion());
                        $item->setApoderadoDireccion($napsis->getApoderadoDireccion());
                        $item->setPadreRut($napsis->getPadreRut());
                        $item->setMadreRut($napsis->getMadreRut());
                        $item->setApoderadoRut($napsis->getApoderadoRut());
                    }
                }
            }
            else
            {
                if($item->getMatriculaCompletada())
                {
                    $this->addFlash('danger','El estudiante seleccionado ya está matriculado');
                    return $this->redirectToRoute("matricula_index");
                }
            }

            $form = $this->createForm(MatriculaFormType::class, $item);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $item = $form->getData();
                $item->setFechaActualizacion($ahora);
                $item->setActivo(TRUE);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
                
                $pdfPath = $this->kernel->getProjectDir(). '/public/pdf/';
                $logo = $this->kernel->getProjectDir(). '/public/logo_hoover.jpg';
                $pdfFile = 'matricula-'.($ahora->format('YmdHis')).'-'.uniqid().'.pdf';

                //$pdfCartaCompromiso = $this->kernel->getProjectDir(). '/public/carta-compromiso-1-2022.pdf';

                $foto_apoderado = '';
                $foto_estudiante = '';
                $hayError = FALSE;
                if($item->getFotoApoderado())
                {
                    //$foto_apoderado = $helper->asset($item, 'fotoApoderadoFile');
                    $foto_apoderado = $this->imagineCacheManager->getBrowserPath($helper->asset($item, 'fotoApoderadoFile'), 'mini');
                }
                else
                {
                    $hayError = TRUE;
                    $this->addFlash('danger','Debe ingresar fotografia de apoderado');
                }
                if($item->getFotoEstudiante())
                {
                    //$foto_estudiante = $helper->asset($item, 'fotoEstudianteFile');
                    $foto_estudiante = $this->imagineCacheManager->getBrowserPath($helper->asset($item, 'fotoEstudianteFile'), 'mini');
                }
                else
                {
                    $hayError = TRUE;
                    $this->addFlash('danger','Debe ingresar fotografia de estudiante');
                }

                if(!$hayError)
                {
                    $html = $this->renderView(
                        'matricula/formulario.pdf.html.twig',
                        array(
                            'item' => $item,
                            'logo' => $logo,
                            'foto_apoderado' => $foto_apoderado,
                            'foto_estudiante' => $foto_estudiante,
                            )
                    );

                    $secciones_html = explode("[SALTO_SECCION]", $html);

                    $pdf = $this->pdfController->create('', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                    $pdf->SetPrintHeader(false);
                    $pdf->SetPrintFooter(false);
                    $pdf->SetMargins(10, 10, 10); //(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                    $pdf->SetFont ('helvetica', '', 10, '', 'default', true );
                    
                    $pdf->AddPage('P', 'LETTER');
                    //$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

                    foreach($secciones_html as $seccion_html)
                    {
                        $pdf->startTransaction(); 
                        $start_page = $pdf->getPage();                       
                        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $seccion_html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                        $end_page = $pdf->getPage();
                        if  ($end_page != $start_page) {
                            $pdf->rollbackTransaction(true); // don't forget the true
                            $pdf->AddPage();
                            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $seccion_html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                        }else{
                            $pdf->commitTransaction();     
                        } 
                    }

                    $pdf->Output($pdfPath.$pdfFile,'F'); 

                    $item->setPdf($pdfFile);
                    $this->entityManager->persist($item);
                    $this->entityManager->flush();
                    $this->addFlash("success", 'Formulario guardado');
                    $this->session->set('SESION_MATRICULA_ID', $item->getId());

                    $titulo = '[Colegio Hoover] Ficha de Matricula guardada';
                    $contenido = $this->twig->render('matricula/email_matricula.html.twig', [
                        'item' => $item,
                    ]);

                    $message = (new \Swift_Message($titulo))
                        ->setFrom('colegiohoover@samuelvasquez.cl')
                        ->setTo($item->getSolicitanteCorreoElectronico())
                        ->setBcc('colegiohoover@samuelvasquez.cl')
                        ->setBody($contenido,'text/html')
                        ->attach(\Swift_Attachment::fromPath($pdfPath.$pdfFile)->setFilename($pdfFile));
                        ;
                    $this->mailer->send($message);

                    return $this->redirectToRoute("matricula_apoderado_exitoso");
                }
            }
            return $this->render("matricula/apoderado_ficha.html.twig", array("form" => $form->createView()));
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/matricula/apoderado/exitoso/", name="matricula_apoderado_exitoso")
     */
    public function apoderado_exitoso(Request $request): Response
    {
        try{
            $id = $this->session->get('SESION_MATRICULA_ID');
            if(!$id)
            {
                return $this->redirectToRoute("matricula_index");
            }

            $item = $this->matriculaRepository->findOneById($id);
            if(!$item)
            {
                return $this->redirectToRoute("matricula_index");
            }
           
            return $this->render("matricula/apoderado_exitoso.html.twig", ["item" => $item]);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }    

    /**
     * @Route("/main/matricula/profesor/", name="matricula_profesor")
     */
    public function profesor(Request $request): Response
    {
        try{
            $busqueda = false;
            $dto = $this->session->get('SESION_MATRICULA_DTO');
            if(!$dto)
            {
                $dto = new MatriculaBuscadorDto();
            }
            else
            {
                $busqueda = true;
                if($dto->curso)
                {
                    $dto->curso = $this->entityManager->merge($dto->curso);
                }
            }

            $form = $this->createForm(MatriculaBuscadorFormType::class, $dto);
            $form->handleRequest($request);
            $lista = null;
            if ($form->isSubmitted() && $form->isValid()) {
                $dto = $form->getData();
                $busqueda = true;
            }

            if($busqueda)
            {
                $rut = str_replace(' ','',str_replace('.','',str_replace('-','',$dto->rut)));
                if($rut != '')
                {
                    $rut = substr($rut,0,strlen($rut)-1).'-'.substr($rut,strlen($rut)-1,1);
                }
                $lista = $this->matriculaRepository->findByBuscador($rut, $dto->curso, $dto->nombres, $dto->apellidos, $dto->matriculaCompletada);
            }

            $this->session->set('SESION_MATRICULA_DTO', $dto);
            
            return $this->render("matricula/profesor.html.twig", array(
                'form' => $form->createView(),
                'busqueda' => $busqueda,
                'lista' => $lista
            ));
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/matricula/profesor/modificar/{id}/", name="matricula_profesor_modificar_ficha")
     */
    public function profesor_modificar_ficha(Request $request, UploaderHelper $helper, Matricula $item): Response
    {
        try{

            if($item->getMatriculaCompletada())
            {
                $this->addFlash('danger','El estudiante seleccionado ya está matriculado');
                return $this->redirectToRoute("matricula_profesor");
            }

            $ahora = new \DateTime('now');

            $form = $this->createForm(MatriculaFormType::class, $item);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $item = $form->getData();
                $item->setFechaActualizacion($ahora);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
                
                $pdfPath = $this->kernel->getProjectDir(). '/public/pdf/';
                $logo = $this->kernel->getProjectDir(). '/public/logo_hoover.jpg';
                $pdfFile = 'matricula-'.($ahora->format('YmdHis')).'-'.uniqid().'.pdf';

                $foto_apoderado = '';
                $foto_estudiante = '';
                $hayError = FALSE;
                if($item->getFotoApoderado())
                {
                    //$foto_apoderado = $helper->asset($item, 'fotoApoderadoFile');
                    $foto_apoderado = $this->imagineCacheManager->getBrowserPath($helper->asset($item, 'fotoApoderadoFile'), 'mini');
                }
                else
                {
                    $hayError = TRUE;
                    $this->addFlash('danger','Debe ingresar fotografia de apoderado');
                }
                if($item->getFotoEstudiante())
                {
                    //$foto_estudiante = $helper->asset($item, 'fotoEstudianteFile');
                    $foto_estudiante = $this->imagineCacheManager->getBrowserPath($helper->asset($item, 'fotoEstudianteFile'), 'mini');
                }
                else
                {
                    $hayError = TRUE;
                    $this->addFlash('danger','Debe ingresar fotografia de estudiante');
                }

                if(!$hayError)
                {
                    $html = $this->renderView(
                        'matricula/formulario.pdf.html.twig',
                        array(
                            'item' => $item,
                            'logo' => $logo,
                            'foto_apoderado' => $foto_apoderado,
                            'foto_estudiante' => $foto_estudiante,                        
                            )
                    );
                    $secciones_html = explode("[SALTO_SECCION]", $html);

                    $pdf = $this->pdfController->create('', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                    $pdf->SetPrintHeader(false);
                    $pdf->SetPrintFooter(false);
                    $pdf->SetMargins(10, 10, 10); //(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                    $pdf->SetFont ('helvetica', '', 10, '', 'default', true );
                    
                    $pdf->AddPage('P', 'LETTER');
                    //$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                    foreach($secciones_html as $seccion_html)
                    {
                        $pdf->startTransaction(); 
                        $start_page = $pdf->getPage();                       
                        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $seccion_html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                        $end_page = $pdf->getPage();
                        if  ($end_page != $start_page) {
                            $pdf->rollbackTransaction(true); // don't forget the true
                            $pdf->AddPage();
                            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $seccion_html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                        }else{
                            $pdf->commitTransaction();     
                        } 
                    }
                    $pdf->Output($pdfPath.$pdfFile,'F'); 

                    $item->setPdf($pdfFile);
                    $this->entityManager->persist($item);
                    $this->entityManager->flush();
                    $this->addFlash("success", 'Formulario guardado');
                    $this->session->set('SESION_MATRICULA_ID', $item->getId());

                    return $this->redirectToRoute("matricula_profesor");
                }
            }
            return $this->render("matricula/profesor_modificar_ficha.html.twig", array(
                'form' => $form->createView(),
            ));
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('matricula_profesor');
        }
    }
    
     /**
     * @Route("/main/matricula/profesor/nueva_matricula/", name="matricula_profesor_nueva_ficha")
     */
    public function profesor_nueva_ficha(Request $request): Response
    {
        try{
            $this->session->set('SESION_MATRICULA_RUT', '');
            $this->session->set('SESION_MATRICULA_ID', '');
                
            $form = $this->createForm(MatriculaFormularioInicialType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $item = $form->getData();
                $rut = str_replace(' ','',str_replace('.','',str_replace('-','',$item->rut)));
                $rut = substr($rut,0,strlen($rut)-1).'-'.substr($rut,strlen($rut)-1,1);
                $this->session->set('SESION_MATRICULA_RUT', $rut);
                return $this->redirectToRoute("matricula_profesor_nueva_ficha_completar");
            }
            return $this->render("matricula/profesor_nueva_ficha.html.twig", ["form" => $form->createView()]);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('matricula_profesor');
        }
    }

    /**
     * @Route("/main/matricula/profesor/completar_matricula/", name="matricula_profesor_nueva_ficha_completar")
     */
    public function profesor_nueva_ficha_completar(Request $request, UploaderHelper $helper): Response
    {
        try{
            $ahora = new \DateTime('now');

            $rut = $this->session->get('SESION_MATRICULA_RUT');
            if(!$rut)
            {
                return $this->redirectToRoute("matricula_profesor_nueva_ficha");
            }

            $item = $this->matriculaRepository->findEnMatriculaActual($rut);
            if(!$item)
            {
                $item = new Matricula();
                $item->setFechaRegistro($ahora);
                $item->setActivo(TRUE);
                $item->setRut($rut);

                $item_anterior = $this->matriculaRepository->findEnMatriculaAnterior($rut);
                if($item_anterior)
                {
                    $item->setNacionalidad($item_anterior->getNacionalidad());
                    $item->setComuna($item_anterior->getComuna());
                    $item->setConQuienVive($item_anterior->getConQuienVive());
                    $item->setPadreNivelEducacional($item_anterior->getPadreNivelEducacional());
                    $item->setMadreNivelEducacional($item_anterior->getMadreNivelEducacional());
                    $item->setApoderadoParentesco($item_anterior->getApoderadoParentesco());
                    $item->setApoderadoNivelEducacional($item_anterior->getApoderadoNivelEducacional());
                    $item->setQuienRetiraParentesco($item_anterior->getQuienRetiraParentesco());
                    $item->setApellidoPaterno($item_anterior->getApellidoPaterno());
                    $item->setApellidoMaterno($item_anterior->getApellidoMaterno());
                    $item->setNombres($item_anterior->getNombres());
                    $item->setFechaNacimiento($item_anterior->getFechaNacimiento());
                    $item->setCiudadNacimiento($item_anterior->getCiudadNacimiento());
                    $item->setDomicilio($item_anterior->getDomicilio());
                    $item->setNombreTelefonoContacto1($item_anterior->getNombreTelefonoContacto1());
                    $item->setNumeroTelefonoContacto1($item_anterior->getNumeroTelefonoContacto1());
                    $item->setNombreTelefonoContacto2($item_anterior->getNombreTelefonoContacto2());
                    $item->setNumeroTelefonoContacto2($item_anterior->getNumeroTelefonoContacto2());
                    $item->setNombreTelefonoContacto3($item_anterior->getNombreTelefonoContacto3());
                    $item->setNumeroTelefonoContacto3($item_anterior->getNumeroTelefonoContacto3());
                    $item->setColegioProcedencia($item_anterior->getColegioProcedencia());
                    $item->setRepiteCurso($item_anterior->getRepiteCurso());
                    $item->setNecesidadesEducativasEspeciales($item_anterior->getNecesidadesEducativasEspeciales());
                    $item->setPadreNombre($item_anterior->getPadreNombre());
                    $item->setPadreCorreoElectronico($item_anterior->getPadreCorreoElectronico());
                    $item->setPadreProfesion($item_anterior->getPadreProfesion());
                    $item->setPadreLugarTrabajo($item_anterior->getPadreLugarTrabajo());
                    $item->setPadreDireccionTrabajo($item_anterior->getPadreDireccionTrabajo());
                    $item->setMadreNombre($item_anterior->getMadreNombre());
                    $item->setMadreCorreoElectronico($item_anterior->getMadreCorreoElectronico());
                    $item->setMadreProfesion($item_anterior->getMadreProfesion());
                    $item->setMadreLugarTrabajo($item_anterior->getMadreLugarTrabajo());
                    $item->setMadreDireccionTrabajo($item_anterior->getMadreDireccionTrabajo());
                    $item->setApoderadoEsPadre($item_anterior->getApoderadoEsPadre());
                    $item->setApoderadoEsMadre($item_anterior->getApoderadoEsMadre());
                    $item->setApoderadoViveConEstudiante($item_anterior->getApoderadoViveConEstudiante());
                    $item->setApoderadoNombre($item_anterior->getApoderadoNombre());
                    $item->setApoderadoCorreoElectronico($item_anterior->getApoderadoCorreoElectronico());
                    $item->setApoderadoProfesion($item_anterior->getApoderadoProfesion());
                    $item->setApoderadoLugarTrabajo($item_anterior->getApoderadoLugarTrabajo());
                    $item->setApoderadoDireccionTrabajo($item_anterior->getApoderadoDireccionTrabajo());
                    $item->setPadresProfesanReligion($item_anterior->getPadresProfesanReligion());
                    $item->setPadresReligion($item_anterior->getPadresReligion());
                    $item->setQuienRetiraNombre($item_anterior->getQuienRetiraNombre());
                    $item->setObservaciones($item_anterior->getObservaciones());
                    $item->setClinicaTieneSeguro($item_anterior->getClinicaTieneSeguro());
                    $item->setClinicaInstitucionSeguro($item_anterior->getClinicaInstitucionSeguro());
                    $item->setClinicaTelefonoInstitucionSeguro($item_anterior->getClinicaTelefonoInstitucionSeguro());
                    $item->setClinicaTieneEnfermedadCuidadoEspecial($item_anterior->getClinicaTieneEnfermedadCuidadoEspecial());
                    $item->setClinicaEnfermedadCuidadoEspecial($item_anterior->getClinicaEnfermedadCuidadoEspecial());
                    $item->setClinicaRecomendaciones($item_anterior->getClinicaRecomendaciones());
                    $item->setClinicaObservaciones($item_anterior->getClinicaObservaciones());
                    $item->setGenero($item_anterior->getGenero());
                    $item->setApoderadoGenero($item_anterior->getApoderadoGenero());
                    $item->setApoderadoEstadoCivil($item_anterior->getApoderadoEstadoCivil());
                    $item->setTelefono($item_anterior->getTelefono());
                    $item->setCorreoElectronico($item_anterior->getCorreoElectronico());
                    $item->setApoderadoTelefono($item_anterior->getApoderadoTelefono());
                    $item->setApoderadoFechaNacimiento($item_anterior->getApoderadoFechaNacimiento());
                    $item->setPadreDireccion($item_anterior->getPadreDireccion());
                    $item->setMadreDireccion($item_anterior->getMadreDireccion());
                    $item->setPadreTelefono($item_anterior->getPadreTelefono());
                    $item->setMadreTelefono($item_anterior->getMadreTelefono());
                    $item->setApoderadoDireccion($item_anterior->getApoderadoDireccion());
                    $item->setPadreRut($item_anterior->getPadreRut());
                    $item->setMadreRut($item_anterior->getMadreRut());
                    $item->setApoderadoRut($item_anterior->getApoderadoRut());
                    $item->setParentescoTelefonoContacto1($item_anterior->getParentescoTelefonoContacto1());
                    $item->setParentescoTelefonoContacto2($item_anterior->getParentescoTelefonoContacto2());
                    $item->setParentescoTelefonoContacto3($item_anterior->getParentescoTelefonoContacto3());
                }
                else
                {
                    $napsis = $this->napsisRepository->findOneByRut($rut);
                    if($napsis)
                    {
                        $item->setNacionalidad($napsis->getNacionalidad());
                        $item->setComuna($napsis->getComuna());
                        $item->setPadreNivelEducacional($napsis->getPadreNivelEducacional());
                        $item->setMadreNivelEducacional($napsis->getMadreNivelEducacional());
                        $item->setApoderadoParentesco($napsis->getApoderadoParentesco());
                        $item->setApoderadoNivelEducacional($napsis->getApoderadoNivelEducacional());
                        $item->setGenero($napsis->getGenero());
                        $item->setApoderadoGenero($napsis->getApoderadoGenero());
                        $item->setApoderadoEstadoCivil($napsis->getApoderadoEstadoCivil());
                        $item->setApellidoPaterno($napsis->getApellidoPaterno());
                        $item->setApellidoMaterno($napsis->getApellidoMaterno());
                        $item->setNombres($napsis->getNombres());
                        $item->setFechaNacimiento($napsis->getFechaNacimiento());
                        $item->setDomicilio($napsis->getDomicilio());
                        $item->setNumeroTelefonoContacto1($napsis->getNumeroTelefonoContacto1());
                        $item->setPadreNombre($napsis->getPadreNombre());
                        $item->setMadreNombre($napsis->getMadreNombre());
                        $item->setApoderadoNombre($napsis->getApoderadoNombre());
                        $item->setApoderadoCorreoElectronico($napsis->getApoderadoCorreoElectronico());
                        $item->setApoderadoProfesion($napsis->getApoderadoProfesion());
                        $item->setPadresProfesanReligion($napsis->getPadresProfesanReligion());
                        $item->setPadresReligion($napsis->getPadresReligion());
                        $item->setCorreoElectronico($napsis->getCorreoElectronico());
                        $item->setApoderadoTelefono($napsis->getApoderadoTelefono());
                        $item->setApoderadoFechaNacimiento($napsis->getApoderadoFechaNacimiento());
                        $item->setPadreDireccion($napsis->getPadreDireccion());
                        $item->setMadreDireccion($napsis->getMadreDireccion());
                        $item->setApoderadoDireccion($napsis->getApoderadoDireccion());
                        $item->setPadreRut($napsis->getPadreRut());
                        $item->setMadreRut($napsis->getMadreRut());
                        $item->setApoderadoRut($napsis->getApoderadoRut());
                    }
                }
            }
            else
            {
                if($item->getMatriculaCompletada())
                {
                    $this->addFlash('danger','El estudiante seleccionado ya está matriculado');
                    return $this->redirectToRoute("matricula_profesor");
                }
            } 

            $form = $this->createForm(MatriculaFormType::class, $item);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $item = $form->getData();
                $item->setFechaActualizacion($ahora);
                $item->setActivo(TRUE);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
                
                $pdfPath = $this->kernel->getProjectDir(). '/public/pdf/';
                $logo = $this->kernel->getProjectDir(). '/public/logo_hoover.jpg';
                $pdfFile = 'matricula-'.($ahora->format('YmdHis')).'-'.uniqid().'.pdf';

                $foto_apoderado = '';
                $foto_estudiante = '';
                $hayError = FALSE;
                if($item->getFotoApoderado())
                {
                    //$foto_apoderado = $helper->asset($item, 'fotoApoderadoFile');
                    $foto_apoderado = $this->imagineCacheManager->getBrowserPath($helper->asset($item, 'fotoApoderadoFile'), 'mini');
                }
                else
                {
                    $hayError = TRUE;
                    $this->addFlash('danger','Debe ingresar fotografía de apoderado');
                }
                if($item->getFotoEstudiante())
                {
                    //$foto_estudiante = $helper->asset($item, 'fotoEstudianteFile');
                    $foto_estudiante = $this->imagineCacheManager->getBrowserPath($helper->asset($item, 'fotoEstudianteFile'), 'mini');
                }
                else
                {
                    $hayError = TRUE;
                    $this->addFlash('danger','Debe ingresar fotografía de estudiante');
                }

                if(!$hayError)
                {

                    $html = $this->renderView(
                        'matricula/formulario.pdf.html.twig',
                        array(
                            'item' => $item,
                            'logo' => $logo,
                            'foto_apoderado' => $foto_apoderado,
                            'foto_estudiante' => $foto_estudiante,
                            )
                    );

                    $secciones_html = explode("[SALTO_SECCION]", $html);

                    $pdf = $this->pdfController->create('', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
                    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                    $pdf->SetPrintHeader(false);
                    $pdf->SetPrintFooter(false);
                    $pdf->SetMargins(10, 10, 10); //(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                    $pdf->SetFont ('helvetica', '', 10, '', 'default', true );
                    
                    $pdf->AddPage('P', 'LETTER');
                    //$pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);

                    foreach($secciones_html as $seccion_html)
                    {
                        $pdf->startTransaction(); 
                        $start_page = $pdf->getPage();                       
                        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $seccion_html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                        $end_page = $pdf->getPage();
                        if  ($end_page != $start_page) {
                            $pdf->rollbackTransaction(true); // don't forget the true
                            $pdf->AddPage();
                            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $seccion_html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
                        }else{
                            $pdf->commitTransaction();     
                        } 
                    }

                    $pdf->Output($pdfPath.$pdfFile,'F'); 

                    $item->setPdf($pdfFile);
                    $this->entityManager->persist($item);
                    $this->entityManager->flush();
                    $this->addFlash("success", 'Formulario guardado');
                    $this->session->set('SESION_MATRICULA_ID', $item->getId());

                    return $this->redirectToRoute("matricula_profesor");
                }
            }
            return $this->render("matricula/profesor_nueva_ficha_completar.html.twig", ["form" => $form->createView()]);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('matricula_profesor');
        }
    }

    /**
     * @Route("/main/matricula/profesor/matricula_completada/{id}/", name="matricula_profesor_matricula_completada")
     */
    public function profesor_matricula_completada(Request $request, Matricula $item): Response
    {
        try{
            if($item->getMatriculaCompletada())
            {
                $this->addFlash('danger','El estudiante seleccionado ya está matriculado');
                return $this->redirectToRoute("matricula_profesor");
            }

            $ahora = new \DateTime('now');

            $item->setMatriculaCompletada(TRUE);
            $item->setMatriculaUsuario($this->getUser()->getUsername());
            $item->setMatriculaFecha($ahora);

            $this->entityManager->persist($item);
            $this->entityManager->flush();

            $this->addFlash("success", 'Matricula completada');

            return $this->redirectToRoute("matricula_profesor");
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('matricula_profesor');
        }
    }

    
    /**
     * @Route("/main/matricula/profesor/excel/",name="matricula_profesor_excel")
     */
    public function profesor_excel(Request $request)
    {
        try{
            $dto = $this->session->get('SESION_MATRICULA_DTO');
            if(!$dto)
            {
                $this->addFlash('danger','Debe realizar busqueda');
                return $this->redirectToRoute('matricula_profesor');
            }

            $rut = str_replace(' ','',str_replace('.','',str_replace('-','',$dto->rut)));
            if($rut != '')
            {
                $rut = substr($rut,0,strlen($rut)-1).'-'.substr($rut,strlen($rut)-1,1);
            }
            $lista = $this->matriculaRepository->findByBuscador($rut, $dto->curso, $dto->nombres, $dto->apellidos, $dto->matriculaCompletada);

            $spreadsheet = new Spreadsheet();
            /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
            $sheet = $spreadsheet->getActiveSheet();
                                
            $sheet->setCellValue('A1', 'Nro');
            $sheet->setCellValue('B1', 'RUN');
            $sheet->setCellValue('C1', 'Apellido Paterno');
            $sheet->setCellValue('D1', 'Apellido Materno');
            $sheet->setCellValue('E1', 'Nombres');
            $sheet->setCellValue('F1', 'Curso');
            $sheet->setCellValue('G1', 'Apoderado');
            $sheet->setCellValue('H1', 'Teléfono');
            $sheet->setCellValue('I1', 'Correo Electrónico');
            
            $fila = 1;
            $i = 0;
            foreach($lista as $item)
            {
                $i++;
                $fila++;
                $sheet->setCellValue('A'.$fila, $i);
                $sheet->setCellValue('B'.$fila, $item->getRut());
                $sheet->setCellValue('C'.$fila, $item->getApellidoPaterno());
                $sheet->setCellValue('D'.$fila, $item->getApellidoMaterno());
                $sheet->setCellValue('E'.$fila, $item->getNombres());
                $sheet->setCellValue('F'.$fila, $item->getCurso());
                $sheet->setCellValue('G'.$fila, $item->getApoderadoNombre());
                $sheet->setCellValue('H'.$fila, $item->getApoderadoTelefono());
                $sheet->setCellValue('I'.$fila, $item->getApoderadoCorreoElectronico());
            }

            //$sheet->setAutoFilter($sheet->calculateWorksheetDimension());

            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);

            $sheet->getStyle('A1:I1')->getFont()->setBold( true );
            $sheet->getStyle('A1:I1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

            // Create your Office 2007 Excel (XLSX Format)
            $writer = new Xlsx($spreadsheet);
            
            // Create a Temporary file in the system
            $ahora = new \DateTime('now');
            $fileName = 'MATRICULAS-'.($ahora->format('Ymd-Hi')).'.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            
            // Create the excel file in the tmp directory of the system
            $writer->save($temp_file);
            
            // Return the excel file as an attachment
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/main/matricula/profesor/eliminar/{id}/", name="matricula_profesor_eliminar")
     */
    public function profesor_modificar_eliminar(Request $request, Matricula $item): Response
    {
        try{
                $item->setActivo(FALSE);
                $this->entityManager->persist($item);
                $this->entityManager->flush();
                return $this->redirectToRoute("matricula_profesor");
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('matricula_profesor');
        }
    }
}


<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use WhiteOctober\TCPDFBundle\Controller\TCPDFController;
use SGK\BarcodeBundle\Generator\Generator;
use App\DTO\BibliotecaBuscadorDto;
use App\DTO\BibliotecaEjemplarAgregarDto;
use App\DTO\BibliotecaEjemplarDto;
use App\DTO\BibliotecaPersonaDto;
use App\DTO\BibliotecaLibroEstanteDto;
use App\Entity\Autor;
use App\Entity\Editorial;
use App\Entity\ConfiguracionCodigo;
use App\Entity\Curso;
use App\Entity\Ejemplar;
use App\Entity\Libro;
use App\Entity\Persona;
use App\Entity\Prestamo;
use App\Form\BibliotecaAutorType;
use App\Form\BibliotecaBuscadorType;
use App\Form\BibliotecaEditorialType;
use App\Form\BibliotecaEjemplarType;
use App\Form\BibliotecaEjemplarAgregarType;
use App\Form\BibliotecaEjemplarEditarType;
use App\Form\BibliotecaLibroType;
use App\Form\BibliotecaPersonaType;
use App\Form\BibliotecaEstanteType;
use App\Repository\AutorRepository;
use App\Repository\ConfiguracionCodigoRepository;
use App\Repository\CursoRepository;
use App\Repository\EditorialRepository;
use App\Repository\EjemplarRepository;
use App\Repository\LibroRepository;
use App\Repository\PersonaRepository;
use App\Repository\PrestamoRepository;
use App\Kernel;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class BibliotecaController extends AbstractController
{
    private $entityManager;
    private $session;
    private $mailer;
    private $kernel;
    private $pdfController;
    private $twig;
    private $autorRepository;
    private $configuracionCodigoRepository;
    private $cursoRepository;
    private $editorialRepository;
    private $ejemplarRepository;
    private $libroRepository;
    private $personaRepository;
    private $prestamoRepository;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session, 
        \Swift_Mailer $mailer, KernelInterface $kernel, TCPDFController $pdfController, Environment $twig,
        AutorRepository $autorRepository,
        ConfiguracionCodigoRepository $configuracionCodigoRepository, CursoRepository $cursoRepository, 
        EditorialRepository $editorialRepository, EjemplarRepository $ejemplarRepository, 
        LibroRepository $libroRepository, PersonaRepository $personaRepository,
        PrestamoRepository $prestamoRepository)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->kernel = $kernel;
        $this->pdfController = $pdfController;
        $this->twig = $twig;
        $this->autorRepository = $autorRepository;
        $this->configuracionCodigoRepository = $configuracionCodigoRepository;
        $this->cursoRepository = $cursoRepository;
        $this->editorialRepository = $editorialRepository;
        $this->ejemplarRepository = $ejemplarRepository;
        $this->libroRepository = $libroRepository;
        $this->personaRepository = $personaRepository;
        $this->prestamoRepository = $prestamoRepository;
    }

    /**
     * @Route("/biblioteca/menu/", name="biblioteca-menu")
     */
    public function menu(Request $request)
    {
        try{
            $this->session->set('SESION_BIBLIOTECA_BUSCADOR', null);
            return $this->redirectToRoute('biblioteca');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/biblioteca/prestamos/menu/", name="biblioteca-prestamos-menu")
     */
    public function prestamosMenu(Request $request)
    {
        try{
            $this->session->set('SESION_BIBLIOTECA_PERSONA', null);
            return $this->redirectToRoute('biblioteca-prestamos');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/biblioteca/", name="biblioteca")
     */
    public function index(Request $request)
    {
        try{
            $lista = null;
            $busqueda = FALSE;

            $buscadorDto = $this->session->get('SESION_BIBLIOTECA_BUSCADOR');
            if(!$buscadorDto)
            {
                $buscadorDto = new BibliotecaBuscadorDto();
            }
            else
            {
                $busqueda = TRUE;
                if($buscadorDto->ubicacion)
                    $buscadorDto->ubicacion = $this->entityManager->merge($buscadorDto->ubicacion);
        }

            $form = $this->createForm(BibliotecaBuscadorType::class, $buscadorDto);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $lista = $this->libroRepository->findByBibliotecaBuscador($buscadorDto);
                $this->session->set('SESION_BIBLIOTECA_BUSCADOR', $buscadorDto);
            }
            elseif($busqueda)
            {
                $lista = $this->libroRepository->findByBibliotecaBuscador($buscadorDto);
            }
        
            return $this->render('biblioteca/index.html.twig',
                array(
                    'form' => $form->createView(),  
                    'lista_libros' => $lista,
                    'buscadorDto' => $buscadorDto
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/excel/", name="biblioteca-excel")
     */
    public function excel(Request $request)
    {
        try{
            $buscadorDto = $this->session->get('SESION_BIBLIOTECA_BUSCADOR');
            if(!$buscadorDto)
            {
                return $this->redirectToRoute('biblioteca');
            }
            if($buscadorDto->ubicacion)
                $buscadorDto->ubicacion = $this->entityManager->merge($buscadorDto->ubicacion);

            
            $lista = $this->libroRepository->findByBibliotecaBuscador($buscadorDto);
        
            $spreadsheet = new Spreadsheet();
            
            /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setCellValue('A1', '');
            $sheet->setCellValue('B1', 'ISBN');
            $sheet->setCellValue('C1', 'Nombre');
            $sheet->setCellValue('D1', 'Autor');
            $sheet->setCellValue('E1', 'Editorial');
            $sheet->setCellValue('F1', 'Ejemplares');
            
            $fila = 1;
            $i = 0;
            foreach($lista as $item)
            {
                $i++;
                $fila++;
                $sheet->setCellValue('A'.$fila, $i);
                $sheet->setCellValue('B'.$fila, $item->getIsbn().' ');
                $sheet->setCellValue('C'.$fila, $item->getNombre());
                $autores = '';
                foreach($item->getAutors() as $itemAutor)
                {
                    $autores = $autores.$itemAutor->getNombre().' '; 
                }
                $sheet->setCellValue('D'.$fila, $autores);
                $sheet->setCellValue('E'.$fila, $item->getEditorial());
                $sheet->setCellValue('F'.$fila, $item->getEjemplars()->count());
            }
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);

            $sheet->getStyle('A1:F1')->getFont()->setBold(true);
            $sheet->getStyle('A1:F1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

            // Create your Office 2007 Excel (XLSX Format)
            $writer = new Xlsx($spreadsheet);
            
            // Create a Temporary file in the system
            $fileName = 'Biblioteca.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            
            // Create the excel file in the tmp directory of the system
            $writer->save($temp_file);
            
            // Return the excel file as an attachment
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/libro/{libro}/ver/", name="biblioteca-libro")
     */
    public function libro(Request $request, Libro $libro)
    {
        try{
            return $this->render('biblioteca/libro.html.twig',
                array(
                    'libro' => $libro,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/libro/agregar/", name="biblioteca-libro-agregar")
     */
    public function libroAgregar(Request $request)
    {
        try{
            $libro = new Libro();
            $form = $this->createForm(BibliotecaLibroType::class, $libro);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $libro->setActivo(TRUE);
                $this->entityManager->persist($libro);
                $this->entityManager->flush();

                $this->addFlash('success','Se ha agregado el libro a la biblioteca');
                return $this->redirectToRoute('biblioteca-ejemplar-agregar', array('libro' => $libro->getId()));
            }

            return $this->render('biblioteca/libro-agregar.html.twig',
                array(
                    'form' => $form->createView(),
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/libro/{libro}/editar/", name="biblioteca-libro-editar")
     */
    public function libroEditar(Request $request, Libro $libro)
    {
        try{
            $form = $this->createForm(BibliotecaLibroType::class, $libro);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $this->entityManager->persist($libro);
                $this->entityManager->flush();

                $this->addFlash('success','Se ha editado el libro a la biblioteca');
                return $this->redirectToRoute('biblioteca-libro', array('libro' => $libro->getId()));
            }

            return $this->render('biblioteca/libro-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'libro' => $libro
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/libro/{libro}/eliminar/", name="biblioteca-libro-eliminar")
     */
    public function libroEliminar(Request $request, Libro $libro)
    {
        try{
            $libro->setActivo(FALSE);
            $this->entityManager->persist($libro);
            $this->entityManager->flush();
            $this->addFlash('success','Se ha eliminado el libro de la biblioteca');
            return $this->redirectToRoute('biblioteca');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/libro/{libro}/ejemplar/agregar/", name="biblioteca-ejemplar-agregar")
     */
    public function ejemplarAgregar(Request $request, Libro $libro)
    {
        try{
            $ejemplarDto = new BibliotecaEjemplarAgregarDto();
            $form = $this->createForm(BibliotecaEjemplarAgregarType::class, $ejemplarDto);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $codigoInicial = $ejemplarDto->codigoInicial;
                if($codigoInicial == 0)
                {
                    $codigoInicial = $this->ejemplarRepository->findNextCodigoAleatorio();
                }

                for($contador = 0; $contador < $ejemplarDto->copias; $contador++)
                {
                    $codigoActual = $codigoInicial + $contador;

                    $ejemplar = new Ejemplar();
                    $ejemplar->setActivo(TRUE);
                    $ejemplar->setLibro($libro);
                    $ejemplar->setEstadoLibro($ejemplarDto->estado);
                    $ejemplar->setFechaIncorporacion(new \DateTime());
                    $ejemplar->setUbicacion($ejemplarDto->ubicacion);
                    $ejemplar->setCodigo($codigoActual);
                    $ejemplar->setCodigoImpreso(FALSE);
                    $this->entityManager->persist($ejemplar);
                }

                $this->entityManager->flush();

                $this->addFlash('success','Se han agregado los ejemplares a la biblioteca');
                return $this->redirectToRoute('biblioteca-libro', array('libro' => $libro->getId()));
            }

            return $this->render('biblioteca/ejemplar-agregar.html.twig',
                array(
                    'form' => $form->createView(),
                    'libro' => $libro
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/libro/{libro}/ejemplar/{ejemplar}/editar/", name="biblioteca-ejemplar-editar")
     */
    public function ejemplarEditar(Request $request, Libro $libro, Ejemplar $ejemplar)
    {
        try{
            $form = $this->createForm(BibliotecaEjemplarEditarType::class, $ejemplar);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $this->entityManager->persist($ejemplar);
                $this->entityManager->flush();

                $this->addFlash('success','Se han actualizado los ejemplares a la biblioteca');
                return $this->redirectToRoute('biblioteca-libro', array('libro' => $libro->getId()));
            }

            return $this->render('biblioteca/ejemplar-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'libro' => $libro,
                    'ejemplar' => $ejemplar,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/libro/{libro}/ejemplar/{ejemplar}/eliminar/", name="biblioteca-ejemplar-eliminar")
     */
    public function ejemplarEliminar(Request $request, Libro $libro, Ejemplar $ejemplar)
    {
        try{
            $ejemplar->setActivo(FALSE);
            $this->entityManager->persist($ejemplar);
            $this->entityManager->flush();

            $this->addFlash('success','Se han eliminado el ejemplar de la biblioteca');
            return $this->redirectToRoute('biblioteca-libro', array('libro' => $libro->getId()));
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/imprimir-codigos/", name="biblioteca-imprimir-codigos")
     */
    public function imprimirCodigos(Request $request)
    {
        try{
            return $this->render('biblioteca/imprimir-codigos.html.twig',
                array(
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

     /**
     * @Route("/biblioteca/imprimir-codigos/no-impresos/", name="biblioteca-imprimir-codigos-no-impresos")
     */
    public function imprimirCodigosNoImpresos(Request $request)
    {
        try{
            $lista_ejemplar = $this->ejemplarRepository->findCodigosNoImpresos();
            
            $ahora = new \DateTime();
            foreach($lista_ejemplar as $item_ejemplar)
            {
                $item_ejemplar->setFechaImpresionCodigo($ahora);
                $this->entityManager->persist($item_ejemplar);
            }

            $this->entityManager->flush();
            return $this->render('biblioteca/imprimir-codigos-no-impresos.html.twig',
                array(
                    'lista' => $lista_ejemplar,
                    'ahora' => $ahora,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }
    
    /**
     * @Route("/biblioteca/imprimir-codigos/no-impresos/{ahora}/pdf/", name="biblioteca-imprimir-codigos-no-impresos-pdf")
     */
    public function imprimirCodigosNoImpresosPdf(Request $request, TCPDFController $pdfController, \DateTime $ahora)
    {
        try{
            $item_configuracion_codigo = $this->configuracionCodigoRepository->findConfiguracion();
            $lista_ejemplar = $this->ejemplarRepository->findCodigosNoImpresosByFecha($ahora);

            // create new PDF document
            $pdf = $pdfController->create(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $pdf->SetMargins($item_configuracion_codigo->getMargenIzquierda(), $item_configuracion_codigo->getMargenSuperior(), $item_configuracion_codigo->getMargenDerecha()); //(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(0);
            $pdf->SetFooterMargin(0);   
            $pdf->SetAutoPageBreak(TRUE, 0);
            $font_size = $pdf->pixelsToUnits('20');
            $pdf->SetFont ('helvetica', '', $font_size , '', 'default', true );

            $page_format = array(
                'MediaBox' => array ('llx' => 0, 'lly' => 0, 'urx' => $item_configuracion_codigo->getAnchoPagina(), 'ury' => $item_configuracion_codigo->getLargoPagina()),
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
                CODIGO
                NOMBRE LIBRO
                */

            $w = $item_configuracion_codigo->getAnchoPagina() 
                - $item_configuracion_codigo->getMargenIzquierda()
                - $item_configuracion_codigo->getMargenDerecha()
                ;

            $h_codigo = 18;
            $h_nombre = 10;
            
            $x = $item_configuracion_codigo->getMargenIzquierda();
            $y = $item_configuracion_codigo->getMargenSuperior();

            foreach($lista_ejemplar as $item_ejemplar)
            {
                $pdf->AddPage('L', $page_format, 'mm', false);
                $codigo = strval($item_ejemplar->getCodigo());
                $pdf->writeHTMLCell($w, $h_nombre, $x, $y + $h_codigo, $item_ejemplar->getLibro()->getNombre(), 0, 0, false, false, 'C', true);    
                $pdf->write1DBarcode($codigo, 'C128', $x + 10, $y, $w, $h_codigo, 0.4, $style, 'N');
            }

            $pdf->Output("CodigosBarra.pdf",'I'); // This will output the PDF as a response directly
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/imprimir-codigos/no-impresos/{ahora}/ok/", name="biblioteca-imprimir-codigos-no-impresos-ok")
     */
    public function imprimirCodigosNoImpresosOk(Request $request, \DateTime $ahora)
    {
        try{
            $lista_ejemplar = $this->ejemplarRepository->findCodigosNoImpresosByFecha($ahora);

            foreach($lista_ejemplar as $item_ejemplar)
            {
                $item_ejemplar->setCodigoImpreso(TRUE);
                $this->entityManager->persist($item_ejemplar);
            }

            $this->entityManager->flush();
            return $this->redirectToRoute('biblioteca-imprimir-codigos');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamos/", name="biblioteca-prestamos")
     */
    public function prestamos(Request $request)
    {
        try
        {
            $dtoPersona = new BibliotecaPersonaDto();
            $dtoEjemplar = new BibliotecaEjemplarDto();
            $formPersona = $this->createForm(BibliotecaPersonaType::class, $dtoPersona);
            $formPersona->handleRequest($request);
            $formEjemplar = $this->createForm(BibliotecaEjemplarType::class, $dtoEjemplar);
            $formEjemplar->handleRequest($request);
            $persona = null;
            $ejemplar = null;

            if ($formPersona->isSubmitted() && $formPersona->isValid()) 
            {
                $this->session->set('SESION_BIBLIOTECA_PERSONA', $dtoPersona);
                $persona = $this->personaRepository->findOneByCredencial($dtoPersona->credencial);
                if(!$persona)
                {
                    $this->addFlash('danger','No se encuentra profesor o alumno con la credencial ingresada');
                }
            }
            else 
            {
                $dtoPersona = $this->session->get('SESION_BIBLIOTECA_PERSONA');
                if($dtoPersona)
                {
                    $persona = $this->personaRepository->findOneByCredencial($dtoPersona->credencial);
                    if(!$persona)
                    {
                        $this->addFlash('danger','No se encuentra profesor o alumno con la credencial ingresada');
                    }
                }
            }
        
            if ($formEjemplar->isSubmitted() && $formEjemplar->isValid()) 
            {
                $ejemplar = $this->ejemplarRepository->findOneByCodigo($dtoEjemplar->codigo);
                if(!$ejemplar)
                {
                    $this->addFlash('danger','No se encuentra libro o material con el codigo ingresado');
                }
            }

            if($persona && $ejemplar)
            {
                $prestamo = new Prestamo();
                $prestamo->setPersona($persona);
                $prestamo->setEjemplar($ejemplar);
                $prestamo->setFechaPrestamo(new \DateTime());
                $devolver = (new \DateTime())->add(new \DateInterval('P'.$ejemplar->getLibro()::DIAS_DE_PRESTAMO.'D'));
                $prestamo->setFechaDevolucion($devolver);
                $prestamo->setEsDevuelto(FALSE);
                
                $this->entityManager->persist($prestamo);
                $this->entityManager->flush();
                
                $this->addFlash('success','Se ha registrado el préstamo en la biblioteca');
                return $this->redirectToRoute('biblioteca-prestamos');
            }
    
            
            return $this->render('biblioteca/prestamos.html.twig',
                array(
                    'formPersona' => $formPersona->createView(),
                    'formEjemplar' => $formEjemplar->createView(),
                    'persona' => $persona,
                    'ejemplar' => $ejemplar,
                )
            );
        }
        catch(\Exception $e)
        {
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamos/renovar/{prestamo}/", name="biblioteca-prestamos-renovar")
     */
    public function prestamosRenovar(Request $request, Prestamo $prestamo)
    {
        try{
            $prestamo->setFechaRenovacion(new \DateTime());
            $devolver = (new \DateTime())->add(new \DateInterval('P'.$prestamo->getEjemplar()->getLibro()::DIAS_DE_PRESTAMO.'D'));
            $prestamo->setFechaDevolucion($devolver);

            $this->entityManager->persist($prestamo);
            $this->entityManager->flush();
            
            $this->addFlash('success','Se ha renovado el préstamo en la biblioteca');
            return $this->redirectToRoute('biblioteca-prestamos');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamos/devolver/{prestamo}/", name="biblioteca-prestamos-devolver")
     */
    public function prestamosDevolver(Request $request, Prestamo $prestamo)
    {
        try{
            $prestamo->setFechaRealDevolucion(new \DateTime());
            $prestamo->setEsDevuelto(TRUE);

            $this->entityManager->persist($prestamo);
            $this->entityManager->flush();
            
            $this->addFlash('success','Se ha registrado devolución en la biblioteca');
            return $this->redirectToRoute('biblioteca-prestamos');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/reporte-prestamos/", name="biblioteca-reporte-prestamos")
     */
    public function reportePrestamos(Request $request)
    {
        try
        {
            $lista = $this->prestamoRepository->findBy(
                array('esDevuelto' => FALSE),
                array('fechaDevolucion' => 'ASC')
            );

            return $this->render('biblioteca/reporte-prestamos.html.twig',
                array(
                    'lista' => $lista,
                )
            );
        }
        catch(\Exception $e)
        {
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/reporte-prestamos/excel/", name="biblioteca-reporte-prestamos-excel")
     */
    public function reportePrestamosExcel(Request $request)
    {
        try{
            $lista = $this->prestamoRepository->findBy(
                array('esDevuelto' => FALSE),
                array('fechaDevolucion' => 'ASC')
            );

            $spreadsheet = new Spreadsheet();
            
            /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->setCellValue('B1', 'Reporte de Préstamos');

            $sheet->setCellValue('A3', '');
            $sheet->setCellValue('B3', 'Código');
            $sheet->setCellValue('C3', 'Nombre');
            $sheet->setCellValue('D3', 'Autor');
            $sheet->setCellValue('E3', 'Editorial');
            $sheet->setCellValue('F3', 'Solicitante');
            $sheet->setCellValue('G3', 'Curso');
            $sheet->setCellValue('H3', 'Fecha de Préstamo');
            $sheet->setCellValue('I3', 'Fecha de Devolución');
            $sheet->setCellValue('JK3', 'Plazo');
    
            $fila = 3;
            $i = 0;
            foreach($lista as $prestamo)
            {
                $i++;
                $fila++;
                $sheet->setCellValue('A'.$fila, $i);
                $sheet->setCellValue('B'.$fila, $prestamo->getEjemplar()->getCodigo());
                $sheet->setCellValue('C'.$fila, $prestamo->getEjemplar()->getLibro()->getNombre());
                $nombre_autor = '';
                foreach($prestamo->getEjemplar()->getLibro()->getAutors() as $autor)
                {
                    $nombre_autor = $autor;
                }
                $sheet->setCellValue('D'.$fila, $nombre_autor);
                $sheet->setCellValue('E'.$fila, $prestamo->getEjemplar()->getLibro()->getEditorial());
                $sheet->setCellValue('F'.$fila, $prestamo->getPersona());
                $nombre_curso = '';
                foreach($prestamo->getPersona()->getAlumnos() as $alumno)
                {
                    if($alumno->getCurso()->getAnio()->getVigente())
                    {
                        $nombre_curso = $nombre_curso.' '.$alumno->getCurso();
                    }
                }
                $sheet->setCellValue('G'.$fila, $nombre_curso);
                $sheet->setCellValue('H'.$fila, $prestamo->getFechaPrestamo()->format('d-m-Y'));
                $sheet->setCellValue('I'.$fila, $prestamo->getFechaDevolucion()->format('d-m-Y'));
                $sheet->setCellValue('J'.$fila, $prestamo->getPlazo());
            }
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);

            $sheet->getStyle('A3:J3')->getFont()->setBold(true);
            $sheet->getStyle('A3:J3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

            // Create your Office 2007 Excel (XLSX Format)
            $writer = new Xlsx($spreadsheet);
            
            // Create a Temporary file in the system
            $fileName = 'ReportePrestamos.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            
            // Create the excel file in the tmp directory of the system
            $writer->save($temp_file);
            
            // Return the excel file as an attachment
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/reporte-prestamos/pdf/", name="biblioteca-reporte-prestamos-pdf")
     */
    public function reportePrestamosPdf(Request $request, TCPDFController $pdfController)
    {
        try{
            $lista = $this->prestamoRepository->findBy(
                array('esDevuelto' => FALSE),
                array('fechaDevolucion' => 'ASC')
            );

            $html = $this->renderView(
                'biblioteca/reporte-prestamos.pdf.html.twig',
                array(
                    'lista' => $lista,
                )
            );
            $pdf = $pdfController->create('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->AddPage();
            $pdf->setFontSubsetting(true);
            $pdf->SetFont('helvetica', '', 9, '', true);
            $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
            $pdf->Output("ReportePrestamos.pdf",'I'); // This will output the PDF as a response directly
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/autor/menu/", name="biblioteca-autor-menu")
     */
    public function autorMenu(Request $request)
    {
        try{
                $this->session->set('SESION_BIBLIOTECA_AUTOR', null);
            return $this->redirectToRoute('biblioteca-autor-index');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/autor/index/", name="biblioteca-autor-index")
     */
    public function autorIndex(Request $request)
    {
        try{
            $lista = null;
            $autor = new Autor();
            $form = $this->createForm(BibliotecaAutorType::class, $autor);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $lista = $this->autorRepository->findByBuscador($autor->getNombre());
                $this->session->set('SESION_BIBLIOTECA_AUTOR', $autor);
            }
        
            return $this->render('biblioteca/autor-index.html.twig',
                array(
                    'form' => $form->createView(),  
                    'lista' => $lista,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/autor/agregar/", name="biblioteca-autor-agregar")
     */
    public function autorAgregar(Request $request)
    {
        try{
            $autor = new Autor();
            $form = $this->createForm(BibliotecaAutorType::class, $autor);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $this->entityManager->persist($autor);
                $this->entityManager->flush();

                $this->addFlash('success','Se ha agregado el autor a la biblioteca');
                return $this->redirectToRoute('biblioteca-autor-index');
            }

            return $this->render('biblioteca/autor-agregar.html.twig',
                array(
                    'form' => $form->createView(),
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/autor/{autor}/editar/", name="biblioteca-autor-editar")
     */
    public function autorEditar(Request $request, Autor $autor)
    {
        try{
            $form = $this->createForm(BibliotecaAutorType::class, $autor);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $this->entityManager->persist($autor);
                $this->entityManager->flush();

                $this->addFlash('success','Se ha editado el autor');
                return $this->redirectToRoute('biblioteca-autor-index');
            }

            return $this->render('biblioteca/autor-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'autor' => $autor
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/autor/{autor}/eliminar/", name="biblioteca-autor-eliminar")
     */
    public function autorEliminar(Request $request, Autor $autor)
    {
        try{
            if($autor->getLibros())
            {
                $this->addFlash('danger','No se puede eliminar autor que tiene libros.');
            }
            else
            {
                $this->entityManager->remove($autor);
                $this->entityManager->flush();
                $this->addFlash('success','Se ha eliminado el autor');
            }
            return $this->redirectToRoute('biblioteca-autor-index');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/editorial/menu/", name="biblioteca-editorial-menu")
     */
    public function editorialMenu(Request $request)
    {
        try{
                $this->session->set('SESION_BIBLIOTECA_EDITORIAL', null);
            return $this->redirectToRoute('biblioteca-editorial-index');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }
    
    /**
     * @Route("/biblioteca/editorial/index/", name="biblioteca-editorial-index")
     */
    public function editorialIndex(Request $request)
    {
        try{
            $lista = null;
            $editorial = new Editorial();
            $form = $this->createForm(BibliotecaEditorialType::class, $editorial);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $lista = $this->editorialRepository->findByBuscador($editorial->getNombre());
                $this->session->set('SESION_BIBLIOTECA_EDITORIAL', $editorial);
            }
        
            return $this->render('biblioteca/editorial-index.html.twig',
                array(
                    'form' => $form->createView(),  
                    'lista' => $lista,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/editorial/agregar/", name="biblioteca-editorial-agregar")
     */
    public function editorialAgregar(Request $request)
    {
        try{
            $editorial = new Editorial();
            $form = $this->createForm(BibliotecaEditorialType::class, $editorial);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $this->entityManager->persist($editorial);
                $this->entityManager->flush();

                $this->addFlash('success','Se ha agregado la editorial a la biblioteca');
                return $this->redirectToRoute('biblioteca-editorial-index');
            }

            return $this->render('biblioteca/editorial-agregar.html.twig',
                array(
                    'form' => $form->createView(),
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/editorial/{editorial}/editar/", name="biblioteca-editorial-editar")
     */
    public function editorialEditar(Request $request, Editorial $editorial)
    {
        try{
            $form = $this->createForm(BibliotecaEditorialType::class, $editorial);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $this->entityManager->persist($editorial);
                $this->entityManager->flush();

                $this->addFlash('success','Se ha editado la editorial');
                return $this->redirectToRoute('biblioteca-editorial-index');
            }

            return $this->render('biblioteca/editorial-editar.html.twig',
                array(
                    'form' => $form->createView(),
                    'editorial' => $editorial
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/editorial/{editorial}/eliminar/", name="biblioteca-editorial-eliminar")
     */
    public function editorialEliminar(Request $request, Editorial $editorial)
    {
        try{
            if($editorial->getLibros())
            {
                $this->addFlash('danger','No se puede eliminar editorial que tiene libros.');
            }
            else
            {
                $this->entityManager->remove($editorial);
                $this->entityManager->flush();
                $this->addFlash('success','Se ha eliminado la editorial');
            }
            return $this->redirectToRoute('biblioteca-editorial-index');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/devoluciones/", name="biblioteca-devoluciones")
     */
    public function devoluciones(Request $request)
    {
        try
        {
            $session = new Session();

            $dtoEjemplar = new BibliotecaEjemplarDto();
            $form = $this->createForm(BibliotecaEjemplarType::class, $dtoEjemplar);
            $form->handleRequest($request);
            $ejemplar = null;
            $lista = null;

            if ($form->isSubmitted() && $form->isValid()) 
            {
                $ejemplar = $this->ejemplarRepository->findOneByCodigo($dtoEjemplar->codigo);
                if(!$ejemplar)
                {
                    $this->addFlash('danger','No se encuentra libro o material con el codigo ingresado');
                }
                $dtoEjemplar = new BibliotecaEjemplarDto();
                $form = $this->createForm(BibliotecaEjemplarType::class, $dtoEjemplar);

                if($ejemplar)
                {
                    $contador = 0;
                    $lista = $this->prestamoRepository->findBy(
                        array('esDevuelto' => FALSE, 'ejemplar' => $ejemplar),
                        array('fechaDevolucion' => 'ASC')
                    );

                    foreach($lista as $prestamo)
                    {
                        $contador++;

                        $prestamo->setFechaRealDevolucion(new \DateTime());
                        $prestamo->setEsDevuelto(TRUE);
                        $this->entityManager->persist($prestamo);
                    }
                    if($contador > 0)
                    {
                        $this->entityManager->flush();
                        $this->addFlash('success','Se ha registrado la devolución en la biblioteca');
                    }
                }
            }
            
            return $this->render('biblioteca/devoluciones.html.twig',
                array(
                    'form' => $form->createView(),
                    'ejemplar' => $ejemplar,
                    'lista' => $lista
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamo-masivo/", name="biblioteca-prestamo-masivo")
     */
    public function prestamoMasivo(Request $request)
    {
        try
        {
            $this->session->set('SESION_BIBLIOTECA_CURSO', null);
            $this->session->set('SESION_BIBLIOTECA_PERSONA', null);

            $lista = $this->cursoRepository->findCursosVigentes();
            
            return $this->render('biblioteca/prestamo-masivo.html.twig',
                array(
                    'lista' => $lista,
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamo-seleccionar-curso/{curso}/", name="biblioteca-prestamo-seleccionar-curso")
     */
    public function prestamoSeleccionarCurso(Request $request, Curso $curso)
    {
        try
        {
            $this->session->set('SESION_BIBLIOTECA_CURSO', $curso);
            $this->session->set('SESION_BIBLIOTECA_PERSONA', null);
            return $this->redirectToRoute('biblioteca-prestamo-curso');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamo-seleccionar-persona/{persona}/", name="biblioteca-prestamo-seleccionar-persona")
     */
    public function prestamoSeleccionarPersona(Request $request, Persona $persona)
    {
        try
        {
            $this->session->set('SESION_BIBLIOTECA_PERSONA', $persona);
            return $this->redirectToRoute('biblioteca-prestamo-curso');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamo-curso/", name="biblioteca-prestamo-curso")
     */
    public function prestamoCurso(Request $request)
    {
        try
        {
            $curso = $this->session->get('SESION_BIBLIOTECA_CURSO');
            $persona = $this->session->get('SESION_BIBLIOTECA_PERSONA');
            $lista = $this->personaRepository->findAlumnosByCurso($curso);

            if($curso)
            {
                $curso = $this->entityManager->merge($curso);
            }
            if($persona)
            {
                $persona = $this->entityManager->merge($persona);
            }

            $dtoEjemplar = new BibliotecaEjemplarDto();
            $formEjemplar = $this->createForm(BibliotecaEjemplarType::class, $dtoEjemplar);
            $formEjemplar->handleRequest($request);
            $ejemplar = null;

            if ($formEjemplar->isSubmitted() && $formEjemplar->isValid()) 
            {
                if(!$persona)
                {
                    $this->addFlash('danger','Debe seleccionar un alumno');
                }

                $ejemplar = $this->ejemplarRepository->findOneByCodigo($dtoEjemplar->codigo);
                if(!$ejemplar)
                {
                    $this->addFlash('danger','No se encuentra libro o material con el codigo ingresado');
                }
            }

            if($persona && $ejemplar)
            {
                $prestamo = new Prestamo();
                $prestamo->setPersona($persona);
                $prestamo->setEjemplar($ejemplar);
                $prestamo->setFechaPrestamo(new \DateTime());
                $devolver = (new \DateTime())->add(new \DateInterval('P'.$ejemplar->getLibro()::DIAS_DE_PRESTAMO.'D'));
                $prestamo->setFechaDevolucion($devolver);
                $prestamo->setEsDevuelto(FALSE);
                
                $this->entityManager->persist($prestamo);
                $this->entityManager->flush();
                
                $this->addFlash('success','Se ha registrado el préstamo en la biblioteca');
                return $this->redirectToRoute('biblioteca-prestamo-curso');
            }
            
            return $this->render('biblioteca/prestamo-curso.html.twig',
                array(
                    'curso' => $curso,
                    'persona' => $persona,
                    'lista' => $lista,
                    'formEjemplar' => $formEjemplar->createView(),
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/prestamo-curso/devolver/{prestamo}/", name="biblioteca-prestamo-curso-devolver")
     */
    public function prestamoCursoDevolver(Request $request, Prestamo $prestamo)
    {
        try{
            $prestamo->setFechaRealDevolucion(new \DateTime());
            $prestamo->setEsDevuelto(TRUE);

            $this->entityManager->persist($prestamo);
            $this->entityManager->flush();
            
            $this->addFlash('success','Se ha registrado devolución en la biblioteca');
            return $this->redirectToRoute('biblioteca-prestamo-curso');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }


    /**
     * @Route("/biblioteca/estantes/", name="biblioteca-estantes")
     */
    public function estantes(Request $request)
    {
        try{
            $lista = null;
            $busqueda = FALSE;

            $buscadorDto = $this->session->get('SESION_BIBLIOTECA_BUSCADOR');
            if(!$buscadorDto)
            {
                $buscadorDto = new BibliotecaBuscadorDto();
            }
            else
            {
                $busqueda = TRUE;
                if($buscadorDto->ubicacion)
                    $buscadorDto->ubicacion = $this->entityManager->merge($buscadorDto->ubicacion);
        }

            $form = $this->createForm(BibliotecaBuscadorType::class, $buscadorDto);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                $lista = $this->libroRepository->findByBibliotecaBuscador($buscadorDto);
                $this->session->set('SESION_BIBLIOTECA_BUSCADOR', $buscadorDto);
            }
            elseif($busqueda)
            {
                $lista = $this->libroRepository->findByBibliotecaBuscador($buscadorDto);
            }
        
            return $this->render('biblioteca/estantes.html.twig',
                array(
                    'form' => $form->createView(),  
                    'lista_libros' => $lista,
                    'buscadorDto' => $buscadorDto
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-menu');
        }
    }

    /**
     * @Route("/biblioteca/estantes/{libro}/mover/", name="biblioteca-estantes-mover")
     */
    public function estantesMover(Request $request, Libro $libro)
    {
        try{
            $dto = new BibliotecaLibroEstanteDto();
            $form = $this->createForm(BibliotecaEstanteType::class, $dto);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) 
            {
                foreach($libro->getEjemplars() as $item_ejemplar)
                {
                    $item_ejemplar->setUbicacion($dto->estante);
                    $this->entityManager->persist($item_ejemplar);
                }
                $this->entityManager->flush();

                $this->addFlash('success','Se ha movido el libro');
                return $this->redirectToRoute('biblioteca-estantes');
            }

            return $this->render('biblioteca/estantes-mover.html.twig',
                array(
                    'form' => $form->createView(),
                    'libro' => $libro
                )
            );
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('biblioteca-estantes');
        }
    }

}
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

class CatalogoController extends AbstractController
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
     * @Route("/catalogo/menu/", name="catalogo-menu")
     */
    public function menu(Request $request)
    {
        try{
            $this->session->set('SESION_BIBLIOTECA_BUSCADOR', null);
            return $this->redirectToRoute('catalogo');
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/catalogo/", name="catalogo")
     */
    public function index(Request $request)
    {
        //try{
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
        
            return $this->render('catalogo/index.html.twig',
                array(
                    'form' => $form->createView(),  
                    'lista_libros' => $lista,
                    'buscadorDto' => $buscadorDto
                )
            );
        //} catch(\Exception $e){
        //    $this->addFlash('danger',$e->getMessage());
        //    return $this->redirectToRoute('catalogo-menu');
        //}
    }

    /**
     * @Route("/catalogo/excel/", name="catalogo-excel")
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
            $fileName = 'Catalogo.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);
            
            // Create the excel file in the tmp directory of the system
            $writer->save($temp_file);
            
            // Return the excel file as an attachment
            return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
        } catch(\Exception $e){
            $this->addFlash('danger',$e->getMessage());
            return $this->redirectToRoute('catalogo-menu');
        }
    }
}

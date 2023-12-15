<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\DTO\InventarioBuscadorDto;
use App\Entity\Item;
use App\Form\InventarioBuscadorType;
use App\Form\ItemInventarioType;
use App\Sesiones;

class InventarioController extends AbstractController
{
    /**
     * @Route("/inventario/menu/", name="inventario-menu")
     */
    public function menu(Request $request)
    {
        try{
        $session = new Session();
        $session->set(Sesiones::INVENTARIO_BUSCADOR, null);
        return $this->redirectToRoute('inventario');
    } catch(\Exception $e){
        $this->addFlash('danger',$e->getMessage());
        return $this->redirectToRoute('homepage');
    }
    }

    /**
     * @Route("/inventario/", name="inventario")
     */
    public function index(Request $request)
    {
        try{
        $session = new Session();

        $entityManager = $this->getDoctrine()->getManager();
        $repositoryItem = $this->getDoctrine()->getRepository(Item::class);

        $lista = null;
        $inventarioBuscadorDto = new InventarioBuscadorDto();
        $form = $this->createForm(InventarioBuscadorType::class, $inventarioBuscadorDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $lista = $repositoryItem->findInventarioByBuscador($inventarioBuscadorDto);
            $session->set(Sesiones::INVENTARIO_BUSCADOR, $inventarioBuscadorDto);
        }
       
        return $this->render('inventario/index.html.twig',
            array(
                'form' => $form->createView(),  
                'lista' => $lista,
            )
        );
    } catch(\Exception $e){
        $this->addFlash('danger',$e->getMessage());
        return $this->redirectToRoute('homepage');
    }
    }

    /**
     * @Route("/inventario/excel/", name="inventario-excel")
     */
    public function excel(Request $request)
    {
        try{
        $session = new Session();

        $entityManager = $this->getDoctrine()->getManager();
        $repositoryItem = $this->getDoctrine()->getRepository(Item::class);

        $buscadorDTO = $session->get(Sesiones::INVENTARIO_BUSCADOR);
        if(!$buscadorDTO)
        {
            return $this->redirectToRoute('inventario');
        }
        else
        {
            if($buscadorDTO->tipoItem)
                $buscadorDTO->tipoItem = $entityManager->merge($buscadorDTO->tipoItem);
            if($buscadorDTO->ubicacion)
                $buscadorDTO->ubicacion = $entityManager->merge($buscadorDTO->ubicacion);
        }

        $lista = $repositoryItem->findInventarioByBuscador($buscadorDTO);
       
        $spreadsheet = new Spreadsheet();
        
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('B1', 'Inventario');

        $sheet->setCellValue('A3', '');
        $sheet->setCellValue('B3', 'Codigo');
        $sheet->setCellValue('C3', 'Nombre');
        $sheet->setCellValue('D3', 'Tipo');
        $sheet->setCellValue('E3', 'Ubicación');
        $sheet->setCellValue('F3', 'Estado');
        $sheet->setCellValue('G3', 'Responsable');
        $sheet->setCellValue('H3', 'Fecha de Incorporación');
        $sheet->setCellValue('I3', 'Marca');
        $sheet->setCellValue('J3', 'Modelo');
        $sheet->setCellValue('K3', 'Observaciones');
        
        $fila = 3;
        $i = 0;
        foreach($lista as $item)
        {
            $i++;
            $fila++;
            $sheet->setCellValue('A'.$fila, $i);
            $sheet->setCellValue('B'.$fila, $item->getCodigo());
            $sheet->setCellValue('C'.$fila, $item->getNombre());
            $sheet->setCellValue('D'.$fila, $item->getTipoItem());
            $sheet->setCellValue('E'.$fila, $item->getUbicacion());
            $sheet->setCellValue('F'.$fila, $item->getEstadoItem());
            $sheet->setCellValue('G'.$fila, $item->getResponsable());
            if($item->getFechaIncorporacion())
            {
                $sheet->setCellValue('H'.$fila, $item->getFechaIncorporacion()->format('d-m-Y'));
            }
            $sheet->setCellValue('I'.$fila, $item->getMarca());
            $sheet->setCellValue('J'.$fila, $item->getModelo());
            $sheet->setCellValue('K'.$fila, $item->getObservaciones());
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
        $sheet->getColumnDimension('K')->setAutoSize(true);

        $sheet->getStyle('A3:K3')->getFont()->setBold(true);
        $sheet->getStyle('A3:K3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);
        
        // Create a Temporary file in the system
        $fileName = 'Inventario.xlsx';
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
     * @Route("/inventario/agregar/", name="inventario-agregar")
     */
    public function agregar(Request $request)
    {
        try{
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryItem = $this->getDoctrine()->getRepository(Item::class);

        $item = new Item();
        $form = $this->createForm(ItemInventarioType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager->persist($item);
            $entityManager->flush();
            $this->addFlash('success','Se ha agregado el item al inventario');
            return $this->redirectToRoute('inventario');
        }

        return $this->render('inventario/agregar.html.twig',
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
     * @Route("/inventario/editar/{item}/", name="inventario-editar")
     */
    public function editar(Request $request, Item $item)
    {
        try{
        $entityManager = $this->getDoctrine()->getManager();
        $repositoryItem = $this->getDoctrine()->getRepository(Item::class);

        $form = $this->createForm(ItemInventarioType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) 
        {
            $entityManager->persist($item);
            $entityManager->flush();
            $this->addFlash('success','Se ha modificado el item del inventario');
            return $this->redirectToRoute('inventario');
        }

        return $this->render('inventario/editar.html.twig',
            array(
                'form' => $form->createView(),
                'item' => $item,
            )
        );
    } catch(\Exception $e){
        $this->addFlash('danger',$e->getMessage());
        return $this->redirectToRoute('homepage');
    }
    }
}

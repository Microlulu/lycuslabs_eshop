<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Services\PdfService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommandController extends AbstractController
{
    private $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    #[Route('/command', name: 'command')]
    public function index(OrderRepository $orderRepository): Response
    {
        $paid_order = $orderRepository->findBy([
            'user_id' => $this->getUser(),
            'delivery' => true,
        ], [
            'date_order' => 'DESC'
        ]);

        return $this->render('command/index.html.twig', [
            'commands' => $paid_order
        ]);
    }

    #[Route('/show_Command/{id}', name: 'show_command')]
    public function showCommand($id, OrderRepository $orderRepository) {

        $order_selected = $orderRepository->findOneBy([
            'id' => $id
            ]);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('command/pdf_command.html.twig', [
            'command' => $order_selected
        ]);
        return $this->render('command/pdf_command.html.twig', [
            'command' => $order_selected
        ]);

        $this->pdfService->generateHTMLAndFile($html);

        return new Response('', 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }


}

<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Services\PdfService;
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
    public function showCommand($id, OrderRepository $orderRepository): Response {

        $order_selected = $orderRepository->findOneBy([
            'id' => $id
            ]);
        return $this->render('command/pdf_command.html.twig', [
            'command' => $order_selected
        ]);
    }


}

<?php

namespace App\Controller\Admin;

use Exception;
use App\Entity\Voucher;
use App\Form\VoucherType;
use App\Repository\VoucherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/voucher')]
class VoucherController extends AbstractController
{
    #[Route('/voucher_index', name: 'voucher_index', methods: ['GET'])]
    public function index(VoucherRepository $voucherRepository): Response
    {
        return $this->render('voucher/index.html.twig', [
            'vouchers' => $voucherRepository->findAll(),

        ]);
    }

    #[Route('/voucher_new', name: 'voucher_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $voucher = new Voucher();
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($voucher);
            $entityManager->flush();

            return $this->redirectToRoute('voucher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voucher/new.html.twig', [
            'voucher' => $voucher,
            'form' => $form,
        ]);
    }

    #[Route('/voucher_show{id}', name: 'voucher_show', methods: ['GET'])]
    public function show(Voucher $voucher): Response
    {
        return $this->render('voucher/show.html.twig', [
            'voucher' => $voucher,
        ]);
    }

    #[Route('/{id}/voucher_edit', name: 'voucher_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Voucher $voucher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VoucherType::class, $voucher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('voucher_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('voucher/edit.html.twig', [
            'voucher' => $voucher,
            'form' => $form,

        ]);
    }

    #[Route('/voucher_delete{id}', name: 'voucher_delete', methods: ['POST'])]
    public function delete(Request $request, Voucher $voucher, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$voucher->getId(), $request->request->get('_token'))) {
            try{
                $entityManager->remove($voucher);
                $entityManager->flush();
                
            }
            catch(Exception $ex){
                $this->addFlash('message', 'You can\'t delete this voucher');
            }
            }
         

        return $this->redirectToRoute('voucher_index', [], Response::HTTP_SEE_OTHER);
    }
}

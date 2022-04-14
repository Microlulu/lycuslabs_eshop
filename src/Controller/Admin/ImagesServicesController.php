<?php

namespace App\Controller\Admin;

use App\Entity\ImagesServices;
use App\Form\ImagesServicesType;
use App\Repository\ImagesServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/images/services')]
class ImagesServicesController extends AbstractController
{
    #[Route('/', name: 'images_services_index', methods: ['GET'])]
    public function index(ImagesServicesRepository $imagesServicesRepository): Response
    {
        return $this->render('images_services/index.html.twig', [
            'images_services' => $imagesServicesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'images_services_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $imagesService = new ImagesServices();
        $form = $this->createForm(ImagesServicesType::class, $imagesService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($imagesService);
            $entityManager->flush();

            return $this->redirectToRoute('images_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images_services/new.html.twig', [
            'images_service' => $imagesService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'images_services_show', methods: ['GET'])]
    public function show(ImagesServices $imagesService): Response
    {
        return $this->render('images_services/show.html.twig', [
            'images_service' => $imagesService,
        ]);
    }

    #[Route('/{id}/edit', name: 'images_services_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImagesServices $imagesService, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagesServicesType::class, $imagesService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('images_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images_services/edit.html.twig', [
            'images_service' => $imagesService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'images_services_delete', methods: ['POST'])]
    public function delete(Request $request, ImagesServices $imagesService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imagesService->getId(), $request->request->get('_token'))) {
            $entityManager->remove($imagesService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('images_services_index', [], Response::HTTP_SEE_OTHER);
    }
}

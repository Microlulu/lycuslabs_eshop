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
            //Je set le nom de l'image
            $image = $form->get('image')->getData();
            if(!is_null($image)){
                //Je crée un nom unique pour l'image
                $image_new_name = uniqid() . '.' . $image->guessExtension();
                // je déplace l'image vers mon serveur
                $image->move(
                //Premier argument : l'emplacement de l'image (là où la stocker), upload_dir est déclarée dans /config/services.yaml
                    $this->getParameter('upload_dir_services'),
                    //Deuxième argument : le nouveau nom de l'image
                    $image_new_name
                );

            }else{
                // je lui mets une erreur s'il y a un problème
                $this->addFlash('Error', 'your image is not valid');
                return $this->redirectToRoute('images_services_new', [], Response::HTTP_SEE_OTHER);
            }

            $imagesService->setImage($image_new_name);
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
        $old_name_image = $imagesService->getImage();
        $form = $this->createForm(ImagesServicesType::class, $imagesService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // je récupère le fichier image passé dans le formulaire
            $image = $form->get('image')->getData();
            // S'il y a une image, j'enregistre si non j'enregistre une image par défaut
            if (!is_null($image)) {
                // je crée un nom unique pour l'image
                $image_new_name = uniqid() . '.' . $image->guessExtension();
                // je déplace l'image vers mon serveur
                $image->move(
                //Premier argument : l'emplacement de l'image
                    $this->getParameter('upload_dir_services'),
                    //Deuxième argument : le nouveau nom de l'image
                    $image_new_name
                );

                $filename = $this->getParameter('upload_dir_services') . $old_name_image;
                if (file_exists($filename)) {
                    unlink($filename);
                }
            } else {
                // si l'image n'a pas chargé je remet l'ancien nom
                $image_new_name = $old_name_image;
            }
            // j'enregistre l'image dans la BDD avec le nouveau nom
            $imagesService->setImage($image_new_name);
            $entityManager->flush();

            return $this->redirectToRoute('images_services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images_services/edit.html.twig', [
            'images_service' => $imagesService,
            'form' => $form,
        ]);
    }

    #[Route('/images_service_delete{id}', name: 'images_services_delete', methods: ['POST'])]
    public function delete(Request $request, ImagesServices $imagesService, EntityManagerInterface $entityManager): Response
    {
        $filename = $this->isCsrfTokenValid('delete'.$imagesService->getId(), $request->request->get('_token'));

        //Je vérifie si mon fichier existe
        if(file_exists($filename)){
            unlink($filename);
        }
        $entityManager->remove($imagesService);
        $entityManager->flush();



        return $this->redirectToRoute('images_services_index', [], Response::HTTP_SEE_OTHER);
    }
}

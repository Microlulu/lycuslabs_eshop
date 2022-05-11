<?php

namespace App\Controller\Admin;

use App\Entity\Services;
use App\Form\ServicesType;
use App\Repository\ImagesServicesRepository;
use App\Repository\ServicesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/services')]
class ServicesController extends AbstractController
{
    #[Route('/services_index', name: 'services_index', methods: ['GET'])]
    public function index(ServicesRepository $servicesRepository): Response
    {

        return $this->render('services/index.html.twig', [
            'services' => $servicesRepository->findAll(),

        ]);
    }

    #[Route('/service_new', name: 'service_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $service = new Services();
        $form = $this->createForm(ServicesType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Je set le nom de l'image
            $image = $form->get('image')->getData();
            if (!is_null($image)) {
                //Je crée un nom unique pour l'image
                $image_new_name = uniqid() . '.' . $image->guessExtension();
                // je déplace l'image vers mon serveur
                $image->move(
                    //Premier argument : l'emplacement de l'image (la ou la stocker), umpload_dir est déclarée dans /config/services.yaml
                    $this->getParameter('upload_dir_services'),
                    //Deuxième argument : le nouveau nom de l'image
                    $image_new_name
                );
            } else {
                $this->addFlash('Error', 'your image is not valid');
                return $this->redirectToRoute('services_index', [], Response::HTTP_SEE_OTHER);
            }

            $service->setImage($image_new_name);
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('services/new.html.twig', [
            'service' => $service,
            'form' => $form,

        ]);
    }

    #[Route('/service_show{id}', name: 'service_show', methods: ['GET'])]
    public function show(Services $service): Response
    {
        return $this->render('services/show.html.twig', [
            'service' => $service,

        ]);
    }

    #[Route('/{id}/service_edit', name: 'service_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Services $service, EntityManagerInterface $entityManager): Response
    {
        // je reccupère l'ancien nom de l'image
        $old_name_image = $service->getImage();
        $form = $this->createForm(ServicesType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // je reccupere le fichier image passé dans le formulaire
            $image = $form->get('image')->getData();
            // Si il y'a une image  j'enregistre si non j'enregistre une image par défaut
            if (!is_null($image)) {
                // je creer un nom unique pour l'image
                $image_new_name = uniqid() . '.' . $image->guessExtension();
                // je déplace l'image vers mon serveur
                $image->move(
                    //Premier argument : l'emplacement de l'image
                    $this->getParameter('upload_dir_services'),
                    //Deuxieme argument : le nouveau nom de l'image
                    $image_new_name
                );
                
                //je verifie si il y'a déjà une image uploader pour ce service
                $filename = $this->getParameter('upload_dir_services') . $old_name_image;
                if (file_exists($filename)) {
                    unlink($filename);
                }
            } else {
                //si l'image n'a pas chargé je remet l'ancien nom
                $image_new_name = $old_name_image;
            }
            //si il n'y en a pas j'enregitre l'image dans la BDD avec le nouveau nom
            $service->setImage($image_new_name);
            $entityManager->flush();

            return $this->redirectToRoute('services_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('services/edit.html.twig', [
            'service' => $service,
            'form' => $form,

        ]);
    }

    #[Route('/services_delete{id}', name: 'services_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Services $service, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($service);
        $entityManager->flush();
        $filename = $this->getParameter('upload_dir_services') . $service->getImage();
        //je verifie si le fichier existe
        if(file_exists($filename)){
            unlink($filename);
        }
        return $this->redirectToRoute('services_index', [], Response::HTTP_SEE_OTHER);
    }

    /*ici je remplace l'id de mon produit par mon slug pour avoir une url mieux référencée*/
    #[Route('/our_services', name: 'our_services', methods: ['GET'])]
    public function OurServices(ServicesRepository $servicesRepository, ImagesServicesRepository $imagesServicesRepository): Response
    {
        return $this->render('services/ourservices.html.twig', [
            'services' => $servicesRepository->findAll(),

        ]);
    }
    
}

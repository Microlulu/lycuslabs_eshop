<?php

namespace App\Controller\Admin;

use App\Entity\Carousel;
use App\Form\CarouselType;
use App\Repository\CarouselRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/carousel')]
class CarouselController extends AbstractController
{
    #[Route('/carousel_index', name: 'carousel_index', methods: ['GET'])]
    public function index(CarouselRepository $carouselRepository): Response
    {
        return $this->render('carousel/index.html.twig', [
            'carousels' => $carouselRepository->findAll(),
        ]);
    }

    #[Route('/new_carousel', name: 'carousel_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // je creer un nouvel objet Carousel
        $carousel = new Carousel();
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             //Je set le nom de l'image
             $image = $form->get('image')->getData();
             if(!is_null($image)){
                 //Je créer un nom unique pour l'image
                 $image_new_name = uniqid() . '.' . $image->guessExtension();
                 // je déplace l'image vers mon serveur
                 $image->move(
                     //Premier argument : l'emplacement de l'image (là ou la stocker), umpload_dir est déclarée dans /config/services.yaml
                     $this->getParameter('upload_dir_carousel'),
                     //Deuxieme argument : le nouveau nom de l'image
                     $image_new_name
                 );
             
                }else{
                    // je lui mets une erreur si il y'a un problème
                 $this->addFlash('Error', 'your image is not valid');
                 return $this->redirectToRoute('caroussel_new', [], Response::HTTP_SEE_OTHER);
                }
 
            $carousel->setImage($image_new_name);
            $entityManager->persist($carousel);
            $entityManager->flush();

            return $this->redirectToRoute('carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/new.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/carousel_show{id}', name: 'carousel_show', methods: ['GET'])]
    public function show(Carousel $carousel): Response
    {
        return $this->render('carousel/show.html.twig', [
            'carousel' => $carousel,
        ]);
    }

    #[Route('/{id}/carousel_edit', name: 'carousel_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carousel $carousel, EntityManagerInterface $entityManager): Response
    {
        // je reccupere le nom de l'ancienne image
        $old_name_image = $carousel->getImage();
        $form = $this->createForm(CarouselType::class, $carousel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // je reccupere le fichier image passé dans le formulaire
            $image = $form->get('image')->getData();
            // Si il y'a une image  j'enregistre si non j'enregistre une image par défaut
            if(!is_null($image)){
                 // je creer un nom unique pour l'image
             $image_new_name = uniqid() . '.' . $image->guessExtension();
             // je déplace l'image vers mon serveur
             $image->move(
                 //Premier argument : l'emplacement de l'image
                 $this->getParameter('upload_dir_carousel'),
                 //Deuxieme argument : le nouveau nom de l'image
                 $image_new_name
             );
             $filename = $this->getParameter('upload_dir_carousel') . $old_name_image;
             if(file_exists($filename)){
                 unlink($filename);
             }
 
            }else{
                  // si l'image n'a pas chargé je remet l'ancien nom
                  $image_new_name = $old_name_image;
            }
            // j'enregitre l'image dans la BDD avec le nouveau nom
            $carousel->setImage($image_new_name);

            $entityManager->flush();

            return $this->redirectToRoute('carousel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('carousel/edit.html.twig', [
            'carousel' => $carousel,
            'form' => $form,
        ]);
    }

    #[Route('/carousel_delete{id}', name: 'carousel_delete', methods: ['POST'])]
    public function delete(Request $request, Carousel $carousel, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($carousel);
        $entityManager->flush();
        $filename = $this->getParameter('upload_dir') . $carousel->getImage();
        
        //Je verifie si mon fichier existe 
        if(file_exists($filename)){
            unlink($filename);
        }
       

        return $this->redirectToRoute('carousel_index', [], Response::HTTP_SEE_OTHER);
    }
}

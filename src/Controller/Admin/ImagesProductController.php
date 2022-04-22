<?php

namespace App\Controller\Admin;

use App\Entity\ImagesProduct;
use App\Form\ImagesProductType;
use App\Repository\ImagesProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/images/product')]
class ImagesProductController extends AbstractController
{
    #[Route('/', name: 'images_product_index', methods: ['GET'])]
    public function index(ImagesProductRepository $imagesProductRepository): Response
    {
        return $this->render('images_product/index.html.twig', [
            'images_products' => $imagesProductRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'images_product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $imagesProduct = new ImagesProduct();
        $form = $this->createForm(ImagesProductType::class, $imagesProduct);
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
                    $this->getParameter('upload_dir_products'),
                    //Deuxième argument : le nouveau nom de l'image
                    $image_new_name
                );

            }else{
                // je lui mets une erreur s'il y a un problème
                $this->addFlash('Error', 'your image is not valid');
                return $this->redirectToRoute('images_product_new', [], Response::HTTP_SEE_OTHER);
            }

            $imagesProduct->setImage($image_new_name);
            $entityManager->persist($imagesProduct);
            $entityManager->flush();

            return $this->redirectToRoute('images_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images_product/new.html.twig', [
            'images_product' => $imagesProduct,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'images_product_show', methods: ['GET'])]
    public function show(ImagesProduct $imagesProduct): Response
    {
        return $this->render('images_product/show.html.twig', [
            'images_product' => $imagesProduct,
        ]);
    }

    #[Route('/{id}/edit', name: 'images_product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImagesProduct $imagesProduct, EntityManagerInterface $entityManager): Response
    {
        // je récupère l'ancienne image pour pouvoir la setter avec le produit
        $old_name_image = $imagesProduct->getImage();
        $form = $this->createForm(ImagesProductType::class, $imagesProduct);
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
                    $this->getParameter('upload_dir_products'),
                    //Deuxième argument : le nouveau nom de l'image
                    $image_new_name
                );

                $filename = $this->getParameter('upload_dir_products') . $old_name_image;
                if (file_exists($filename)) {
                    unlink($filename);
                }
            } else {
                // si l'image n'a pas chargé je remet l'ancien nom
                $image_new_name = $old_name_image;
            }
            // j'enregistre l'image dans la BDD avec le nouveau nom
            $imagesProduct->setImage($image_new_name);
            $entityManager->flush();

            return $this->redirectToRoute('images_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images_product/edit.html.twig', [
            'images_product' => $imagesProduct,
            'form' => $form,
        ]);
    }

    #[Route('/images_product_delete{id}', name: 'images_product_delete', methods: ['POST'])]
    public function delete(Request $request, ImagesProduct $imagesProduct, EntityManagerInterface $entityManager): Response

    {
        $entityManager->remove($imagesProduct);
        $entityManager->flush();
        $filename = $this->isCsrfTokenValid('delete'.$imagesProduct->getId(), $request->request->get('_token'));

            //Je vérifie si mon fichier existe
        if(file_exists($filename)){
            unlink($filename);
        }


        return $this->redirectToRoute('images_product_index', [], Response::HTTP_SEE_OTHER);
    }
}

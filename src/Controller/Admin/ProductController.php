<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/product')]
class ProductController extends AbstractController
{
    #[Route('/product_index', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new_product', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
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
                    $this->getParameter('upload_dir_products'),
                    //Deuxieme argument : le nouveau nom de l'image
                    $image_new_name
                );
            } else {
                $image_new_name = 'default.png';
            }
            // j'enregitre l'image dans la BDD avec le nouveau nom
            $product->setImage($image_new_name);

            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/product_show{id}', name: 'product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/product_edit', name: 'product_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        // je reccupere l'anciene image pour povoir la setter avec le produit 
        $old_name_image = $product->getImage();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // je reccupère le fichier image passé dans le formulaire
            $image = $form->get('image')->getData();
            // Si il y'a une image  j'enregistre si non j'enregistre une image par défaut
            if (!is_null($image)) {
                // je creer un nom unique pour l'image
                $image_new_name = uniqid() . '.' . $image->guessExtension();
                // je déplace l'image vers mon serveur
                $image->move(
                    //Premier argument : l'emplacement de l'image
                    $this->getParameter('upload_dir_products'),
                    //Deuxieme argument : le nouveau nom de l'image
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
            // j'enregitre l'image dans la BDD avec le nouveau nom
            $product->setImage($image_new_name);

            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/product_delete{id}', name: 'product_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        foreach ($product->getImagesProducts() as $row){
            $filename = $this->getParameter('upload_dir_products') . $row->getImage();
            if(file_exists($filename)){
                unlink($filename);
            }
        }
        return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
    }
}

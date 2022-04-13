<?php

namespace App\Controller\Admin;

use App\Repository\ImagesProductRepository;
use DateTime;
use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/product')]
class ProductController extends AbstractController
{

    #[Route('/product_index', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, ImagesProductRepository $imagesProductRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'imagesproduct' => $imagesProductRepository->findAll()
        ]);
    }

    #[Route('/new_product', name: 'product_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
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
            } else {
                $image_new_name = 'default.png';
            }
            // j'enregistre l'image dans la BDD avec le nouveau nom
            $product->setImage($image_new_name);

            // je set la date du jour
            $date_e = new DateTime();
            $product->setCreatedat($date_e);

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
        // je récupère l'ancienne image pour pouvoir la setter avec le produit
        $old_name_image = $product->getImage();
        $form = $this->createForm(ProductType::class, $product);
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
            $product->setImage($image_new_name);

            // si je modifie mon produit, je set la date du jour dans le champ updatedat de ma BDD
            $date_e = new DateTime();
            $product->setUpdatedat($date_e);

            $entityManager->flush();

            return $this->redirectToRoute('product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/product_delete{id}', name: 'product_delete', methods: ['GET','POST'])]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            // si je supprime ma catégorie, je set la date du jour dans le champ updatedat de ma BDD
            $date_e = new DateTime();
            $product->setDeletedat($date_e);

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

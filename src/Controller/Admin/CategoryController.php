<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/category')]
class CategoryController extends AbstractController
{
    #[Route('/category_index', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),

        ]);
    }

    #[Route('/new_category', name: 'category_new', methods: ['GET', 'POST'])]
    public function new( Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // je set la date du jour
            $date_e = new DateTime();
            $category->setCreatedat($date_e);

            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/new.html.twig', [
            'category' => $category,
            'form' => $form,

        ]);
    }

    #[Route('/category_show{id}', name: 'category_show', methods: ['GET'])]
    public function show( Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,

        ]);
    }

    #[Route('/{id}/category_edit', name: 'category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // si je modifie ma catégorie, je set la date du jour dans le champ updatedat de ma BDD
            $date_e = new DateTime();
            $category->setUpdatedat($date_e);

            $entityManager->flush();

            return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,

        ]);
    }

    #[Route('/category_delete{id}', name: 'category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            // si je supprime ma catégorie, je set la date du jour dans le champ updatedat de ma BDD
            $date_e = new DateTime();
            $category->setDeletedat($date_e);
            
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
    }
}

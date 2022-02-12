<?php

namespace App\Controller;

use App\Controller\Admin\ProductController;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/user_index', name: 'user_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository,UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
              // je rajoute cette ligne dans toutes mes vues publiques et privées pour pouvoir voir le bouton dynamique qui contient la boucle des produits
              'list_product' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new_user', name: 'user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
              // je rajoute cette ligne dans toutes mes vues publiques  et privées pour pouvoir voir le bouton dynamique qui contient la boucle des produits
              'list_product' => $productRepository->findAll(),
        ]);
    }

    #[Route('/user_show{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user, ProductRepository $productRepository): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
              // je rajoute cette ligne dans toutes mes vues publiques  et privées pour pouvoir voir le bouton dynamique qui contient la boucle des produits
              'list_product' => $productRepository->findAll(),
        ]);
    }

    #[Route('/{id}/user_edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
              // je rajoute cette ligne dans toutes mes vues publiques et privées pour pouvoir voir le bouton dynamique qui contient la boucle des produits
              'list_product' => $productRepository->findAll(),
        ]);
    }

    #[Route('/user_delete{id}', name: 'user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}

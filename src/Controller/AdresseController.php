<?php

namespace App\Controller;
use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;
use App\Services\DefaultAdresseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresse')]
class AdresseController extends AbstractController
{
    // j'appelle ma classe defaultAdresseService pour pouvoir appeler les fonctions qu'il ya dedans
    private DefaultAdresseService $defaultAdresseService;

    // je veux initialiser une fois notre service avec une methode construct
    public function __construct(DefaultAdresseService $defaultAdresseService){
        $this->defaultAdresseService = $defaultAdresseService;
    }

    // cette fonction n'est pas appeler
    #[Route('/adresse_index', name: 'adresse_index', methods: ['GET'])]
    public function index(AdresseRepository $adresseRepository): Response
    {
        // vu admin
        // je récupère l'utilisateur pour pouvoir voir toutes les infos en relation avec cet utilisateur
        // si je ne le fais pas je vois toutes les adresses de tout le monde.
        $user=$this->getUser();
        return $this->render('adresse/index.html.twig', [
            'adresses' => $adresseRepository->findBy(['user_id' => $user]),

        ]);
    }

    #[Route('/new_adresse', name: 'adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // je récupère l'utilisateur
        $user =$this->getUser();
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setUserId($user);
            //j'appelle ma fonction globale et ma methode selectByDefault et je lui passe l'adresse et l'user
            $this->defaultAdresseService->selectByDefault($adresse, $user);

            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,

        ]);
    }

    #[Route('/adresse_show/{id}', name: 'adresse_show', methods: ['GET'])]
    public function show(Adresse $adresse): Response
    {
        // je dois proteger mon code car j'ai fais des cruds mais je dois les rendrent propres a chaque utilisateurs
        // je reccupere l'user connecté
        $userConnected=$this->getUser();
        // je reccupere l'adresse de l'user connecté
        $userAdresse=$adresse->getUserId();
        // et je fais une comparaison :
        // si l'user connectz est différent de l'id de l'user a qui appartient l'adresse je le logout.
        if ($userConnected != $userAdresse){
            return $this->redirectToRoute('logout');
        }
        // sinon je lui montre son adresse
        return $this->render('adresse/show.html.twig', [
            'adresse' => $adresse,

        ]);
    }

    #[Route('/adresse_edit/{id}', name: 'adresse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        
        // je dois proteger mon code car j'ai fais des cruds mais je dois les rendrent propres a chaque utilisateurs
        // je reccupere l'user connecté
        $userConnected=$this->getUser();
        // je reccupere l'adresse de l'user connecté
        $userAdresse=$adresse->getUserId();
        // et je fais une comparaison :
        // si l'user connecté est différent de l'id de l'user a qui appartient l'adresse je le logout.
        if ($userConnected != $userAdresse){
            return $this->redirectToRoute('logout');
        }
        // sinon je lui permet de modifier son adresse
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/edit.html.twig', [
            'adresse' => $adresse,
            'form' => $form,

        ]);
    }

    #[Route('/adresse_delete/{id}', name: 'adresse_delete', methods: ['POST'])]
    public function delete(Request $request, Adresse $adresse, EntityManagerInterface $entityManager): Response
    {
        
        // je dois proteger mon code car j'ai fais des cruds mais je dois les rendrent propres a chaque utilisateurs
        // je reccupere l'user connecté
        $userConnected=$this->getUser();
        // je reccupere l'adresse de l'user connecté
        $userAdresse=$adresse->getUserId();
        // et je fais une comparaison :
        // si l'user connecté est différent de l'id de l'user a qui appartient l'adresse je le logout.
        if ($userConnected != $userAdresse){
            return $this->redirectToRoute('logout');
        }
        // sinon je lui permet de supprimer son adresse
        if ($this->isCsrfTokenValid('delete'.$adresse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($adresse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
    }



 
    
}

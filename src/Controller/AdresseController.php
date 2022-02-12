<?php

namespace App\Controller;
use App\Entity\Adresse;
use App\Form\AdresseType;
use App\Repository\AdresseRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/adresse')]
class AdresseController extends AbstractController
{
    // cette fonction n'est pas appeler
    #[Route('/adresse_index', name: 'adresse_index', methods: ['GET'])]
    public function index(AdresseRepository $adresseRepository): Response
    {
        // vu admin
        // je recupere l'utilisateur pour pouvoir voir toutes les infos en relation avec cet utilisateurs
        // si je ne le fais pas je vois toutes les adresses de tout le monde.
        $user=$this->getUser();
        return $this->render('adresse/index.html.twig', [
            'adresses' => $adresseRepository->findBy(['user_id' => $user]),

        ]);
    }

    #[Route('/new_adresse', name: 'adresse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // je reccupere l'utilisateur
        $user =$this->getUser();
        $adresse = new Adresse();
        $form = $this->createForm(AdresseType::class, $adresse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adresse->setUserId($user);
            $adresse->setDelivery(false);
            $entityManager->persist($adresse);
            $entityManager->flush();

            return $this->redirectToRoute('profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('adresse/new.html.twig', [
            'adresse' => $adresse,
            'form' => $form,

        ]);
    }

    #[Route('/adresse_show{id}', name: 'adresse_show', methods: ['GET'])]
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

    #[Route('/{id}/adresse_edit', name: 'adresse_edit', methods: ['GET', 'POST'])]
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

    #[Route('/adresse_delete{id}', name: 'adresse_delete', methods: ['POST'])]
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

    #[Route('/changer-adresse-delivery', name: 'change_adresse_delivery')]
    public function changeAdresse(AdresseRepository $adresseRepository): Response
    {
        // je recupere l'utilisateur pour pouvoir voir tout les adresses qu'il a enregistrer
        // si je ne le fais pas je vois toutes les adresses de tout le monde
        $user = $this->getUser();
        $adresses = $adresseRepository->findBy(['user_id' => $user]);
        // je lui dis compte le nombre d'adresses presente dans la bdd
        if(count($adresses) == 0){
            // si il yen a pas je lui dis va en creer une
            return $this->redirectToRoute('adresse_new');
        }
            // si oui je l'envoie vers la vue liste de ces adresses dans le profil
        return $this->render('profil/account.html.twig', [
            'adresses' => $adresseRepository->findBy(['user_id' => $user]),

        ]);
    }

    #[Route('/select-adresse-delivery{id}', name: 'select_adresse_delivery', methods: ['GET'])]
    public function selectAdresse($id, AdresseRepository $adresseRepository, EntityManagerInterface $manager): Response
    {
        $user =$this->getUser();
        // je reccupere la liste des adresses de livraison de l'user connecté
        $list_adresses = $adresseRepository->findBy(['user_id' => $user]);
        foreach($list_adresses as $row){
            // je passe toutes ses adresses en false
            $row->setDelivery(false);
            $manager->persist($row);
        }
        // je reccupere ladresse selectionner comme étant l'adresse de livraison
        $select_adresse = $adresseRepository->find($id);
        // je la passe a true
        $select_adresse->setDelivery(true);
        // $select_adresse->setLivraison(true);
        $manager->persist($select_adresse);
        $manager->flush();
        return $this->render('profil/account.html.twig', [
            'adresses' => $list_adresses,
 
        ]);
    }

    
}

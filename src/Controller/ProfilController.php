<?php

namespace App\Controller;

use App\Classes\Cart;
use App\Entity\Adresse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function index(Cart $cart, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        return $this->render('profil/account.html.twig', [
            'user' => $user,
            'list_adresses' => $this->getUser()->getAdresses(),
        ]);
    }



    #[Route('/change-adresse-profil', name: 'change_adresse_profil')]
    public function changeAdresse(Request $request, EntityManagerInterface $manager): Response
    {
        $user =$this->getUser();
        $adresse_final = new Adresse();
        $form = $this->createForm(ChangeadresseType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // j'ajoute dès la table adresse une adresse de livraison par defaut
            $adresse_final->setUserId($this->getUser());
            $adresse_final->setFirstname($form->get('firstname')->getData());
            $adresse_final->setLastname($form->get('lastname')->getData());
            $adresse_final->setAdresse($form->get('adresse')->getData());
            $adresse_final->setZipcode($form->get('zipcode')->getData());
            $adresse_final->setCity($form->get('city')->getData());
            $adresse_final->setCountry($form->get('country')->getData());
            $adresse_final->setTelephone($form->get('telephone')->getData());
            $adresse_final->setDelivery(true);
            // je persiste pour l'objet adresse
            $manager->persist( $adresse_final);
            // je persiste pour l'objet user
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('profil');
        }
        return $this->render('profil/account.html.twig', [
            'form'=> $form->createView(),

        ]);
    }
}

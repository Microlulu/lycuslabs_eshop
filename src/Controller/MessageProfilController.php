<?php

namespace App\Controller;

use App\Form\MessagesType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// ce controller est utiliser seulement dans la vue profil lorsqu'un utilisateur est loggé
class MessageProfilController extends AbstractController
{
    #[Route('/message/profil', name: 'message_profil')]
    // je nomme ma fonction pour la rendre plus parlante pour moi selon son utilité: Messageprofil
    public function MessageProfil(ProductRepository $productRepository): Response
    {
        // j'ai creer un formulaire appelé MessagesType, il faut aussi le créer dans le controller comme ci :
        $form = $this->createForm(MessagesType::class);
        // je renvoie ma vue non pas au fichier par default messages_profil/index.html.twig mais a ma vue account qui correspond au profil utilisateur
        // je veux que le client est access a ce formulaire quand il se trouve dans son profil en cliquant sur le bouton contact
        return $this->render('message_profil/index.html.twig', [
           'message_form' => $form->createView(),
            // je rajoute cette ligne dans toutes mes vues publiques et privées pour pouvoir voir le bouton dynamique qui contient la boucle des produits
            'list_product' => $productRepository->findAll(),
        ]);
    }
}

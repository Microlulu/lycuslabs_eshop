<?php

namespace App\Controller;

use App\Form\MessagesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// ce controller est utilisé seulement dans la vue profil lorsqu'un utilisateur est loggé
class MessageProfilController extends AbstractController
{
    #[Route('/message/profil', name: 'message_profil')]
    // je nomme ma fonction pour la rendre plus parlante pour moi selon son utilité: Messageprofil
    public function MessageProfil(): Response
    {
        // j'ai créé un formulaire appelé MessagesType, que je viens aussi créer dans le controller comme ci pour pouvoir l'appeler dans un bouton :
        $form = $this->createForm(MessagesType::class);
        // je renvoie ma vue non pas au fichier par default messages_profil/index.html.twig mais à ma vue account qui correspond au profil utilisateur
        // je veux que le client ait access a ce formulaire quand il se trouve dans son profil en cliquant sur le bouton contact
        return $this->render('message_profil/index.html.twig', [
           'message_form' => $form->createView(),

        ]);
    }
}

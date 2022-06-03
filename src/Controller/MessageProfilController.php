<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use App\Services\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// ce controller est utilisé seulement dans la vue profil lorsqu'un utilisateur est loggé
class MessageProfilController extends AbstractController
{
    #[Route('/message/profil', name: 'message_profil')]
    // je nomme ma fonction pour la rendre plus parlante pour moi selon son utilité: Messageprofil
    public function MessageProfil(Request $request): Response
    {
        // Je crée un nouvel objet contact (on fait une entité et on l'appelle dans une variable objet)
        $message = new Messages();
        // j'ai créé un formulaire ContactType que je viens aussi créer dans mon controller et je lui passe ma variable contact (entité) pour pouvoir la remplir avec les datas que l'user va me donner.

        // j'ai créé un formulaire appelé MessagesType, que je viens aussi créer dans le controller comme ceci pour pouvoir l'appeler dans un bouton :
        $form = $this->createForm(MessagesType::class, $message);
        // je renvoie ma vue non pas au fichier par default messages_profil/index.html.twig mais à ma vue account qui correspond au profil utilisateur
        // je veux que le client ait access a ce formulaire quand il se trouve dans son profil en cliquant sur le bouton contact

        // handleRequest vérifie qu'on a bien une requète d'un formulaire. on map notre formulaire pour pouvoir l'utiliser.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //get Doctrine appelle l'entity manager plus simplement, elle se situe dans le parent de notre contact controller.
            $entityManager = $this->getDoctrine()->getManager();
            $message->setSentDate(new \DateTime());
            $message->setUserId($this->getUser());

            // PERSIST permet de sauvegarder les données
            $entityManager->persist($message);
            // FLUSH sert a envoyer les datas dans la base de donnée
            $entityManager->flush();
            // ICI NOUS AVONS UN DEUXIEME EMAIL CREER PAR MAILJET QUI DIT A L'UTILISATEUR QU'ON A BIEN RECU SON MESSAGE ET QU'ON VA LUI REPONDRE
            $mail = new Mail();
            $content = "Hi " . $message->getUserId()->getFirstname() . " ! We are sorry to hear that you have a problem with your order!<br/><br/> 
            Don't worry, We will read your message with great attention and answer you as soon as possible.<br/>
            Thank you for your patience.";
            $mail->sendSupport($message->getUserId()->getEmail(), $message->getUserId()->getFirstname(), 'We received your message !', $content);
            return $this->redirectToRoute('message_profil_sent_confirmation');

        }
        return $this->render('message_profil/index.html.twig', [
           'message_form' => $form->createView(),

        ]);

    }

    //Route créer pour la page de confirmation du message du profil après un envoi de message
    #[Route('/message_profil_sent_confirmation/', name: 'message_profil_sent_confirmation', methods: ['GET'])]
    public function showMessageProfilConfirmation(): Response
    {
        return $this->render('message_profil/sent_confirmation_message_profil.html.twig');
    }


}

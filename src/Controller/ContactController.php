<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Services\Mail;
use ContainerPX9wni0\getContactRepositoryService;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// ce controller est utilisé pour la page contact publique du site
class ContactController extends AbstractController
{


    #[Route('/contact', name: 'contact')]
    // je nomme la fonction contact
    public function Contact(Request $request): Response
    {
        // Je crée un nouvel objet contact (on fait une entité et on l'appelle dans une variable objet)
        $contact = new Contact();
        // j'ai créé un formulaire ContactType que je viens aussi créer dans mon controller et je lui passe ma variable contact (entité) pour pouvoir la remplir avec les datas que l'user va me donner.
        $form = $this->createForm(ContactType::class,$contact);
        // handleRequest vérifie qu'on a bien une requète d'un formulaire. on map notre formulaire pour pouvoir l'utiliser.
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //get Doctrine appelle l'entity manager plus simplement, elle se situe dans le parent de notre contact controller.
            $entityManager = $this->getDoctrine()->getManager();
            $createAt = new \DateTimeImmutable();
            $contact->setCreatedAt($createAt);
            // PERSIST permet de sauvegarder les données
            $entityManager->persist($contact);
            // FLUSH sert a envoyer les datas dans la base de donnée
            $entityManager->flush();

            //TODO : Envoyer un message a l'administrateur avec le contenu et toutes les infos du message qu'a envoyer l'utilisateur
            //$this->mailer->send((new NotificationEmail())
            //->subject('New message from customer received !')
            //->htmlTemplate('contact/admin-message.html.twig')
            //->from($contact->getEmail())
            //->to($this->adminEmail)
            //->context(['message' => $contact->getMessage(), $contact->getFirstname(),$contact->getLastname(), $contact->getCreatedAt()])
            //);

            // ICI NOUS AVONS UN DEUXIEME EMAIL CREER PAR MAILJET QUI DIT A L'UTILISATEUR QU'ON A BIEN RECU SON MESSAGE ET QU'ON VA LUI REPONDRE
            $mail = new Mail();
            $content = "Hi " . $contact->getFirstname() . " !<br/><br/> 
            We will read your message with great attention and answer you as soon as possible.<br/>
            Thank you for your patience.";
            $mail->sendSupport($contact->getEmail(),$contact->getFirstname(),'We received your message !', $content);
            return $this->redirectToRoute('message_sent_confirmation');
        }

        // je renvoie ensuite ce formulaire à ma vue index de contact
        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
        ]);
    }
    //Route créer pour la page de confirmation après un envoi de message
    #[Route('/message_sent_confirmation/', name: 'message_sent_confirmation', methods: ['GET'])]
    public function showContactConfirmation(): Response
    {
        return $this->render('contact/sent_confirmation_contact.html.twig');
    }



}

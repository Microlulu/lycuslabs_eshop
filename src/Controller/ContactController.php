<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Services\Mail;
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
            return $this->redirectToRoute('home');
        }

        // je renvoie ensuite ce formulaire à ma vue index de contact
        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),
        ]);
    }



}

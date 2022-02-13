<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// ce controller est utiliser pour la page contact publique du site
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    // je nomme la fonction contact
    public function Contact(): Response
    {
        // j'ai crée un formulaire ContactType que je viens aussi créer dans mon controller
        $form = $this->createForm(ContactType::class);
        // je renvoie ensuite ce formulaire a ma vue index de contact
        return $this->render('contact/index.html.twig', [
            'contact_form' => $form->createView(),

        ]);
    }
}

<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// ce controller est utilisé pour la page contact publique du site
class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    // je nomme la fonction contact
    public function Contact(): Response
    {
        // j'ai créé un formulaire ContactType que je viens aussi créer dans mon controller
        $form = $this->createForm(ContactType::class);
        // je renvoie ensuite ce formulaire à ma vue index de contact
        return $this->render('contact/contact_us.html.twig', [
            'contact_form' => $form->createView(),

        ]);
    }
}

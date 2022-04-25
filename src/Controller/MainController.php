<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /* Controller utiliser pour changer la traduction (locale anglais ou locale française*/
    #[Route('/change-locale/{locale}', name: 'change-locale')]
    public function changeLocale($locale, Request $request)
    {
        // On stocke la langue dans la session
        $request->getSession()->set('_locale', $locale);

        // On revient sur la page précédente
        return $this->redirect($request->headers->get('referer'));
    }


    public function index(TranslatorInterface $translator)
    {
        $message = $translator->trans('Your comment is pending approval');

        // ...
    }
}

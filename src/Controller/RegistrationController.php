<?php

namespace App\Controller;

use App\Services\Mail;
use DateTime;
use App\Entity\User;
use App\Controller\SecurityController;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private EntityManagerInterface $entityManager;

    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $entityManager)
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager =$entityManager;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

                // encode the plain password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('Password')->getData()
                    )
                );
                $date_e = new DateTime();
                $user->setCreatedat($date_e);

                $entityManager->persist($user);
                $entityManager->flush();

                // ICI NOUS AVONS UN PREMIERE ENVOI DE MAIL AVEC LE LIEN DE VERIFICATION D'EMAIL
                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('support@lycuslabs.com', 'Lycuslabs'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                // ICI NOUS AVONS UN DEUXIEME EMAIL CREER PAR MAILJET QUI REMERCIE L'UTILISATEUR DE S'ETRE INSCRIT ET LUI RAPPEL DE VERIFIER SON ADRESSE EMAIL
                $mail = new Mail();
                $content = "Hi ".$user->getFirstname(). " !". "<br/><br/> Thank you for your registration!<br/>
                Don't forget to activate your account, a second email will be sent to you. <br/>
                In the meantime, find your favorite products and discover our personalized services on Lycuslabs.";
                $mail->send($user->getEmail(),$user->getFirstname(),'Welcome to Lycuslabs.com !', $content);

                // je pr??cise ?? mon utilisateur que son email a bien ??t?? activ??e et je le redirige sur la page login
                //Todo : faire fonctionner le message flash
                $this->addFlash('success', 'Your email address has been verified.');
                return $this->redirectToRoute('register_confirmation');

                /*
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                ); */


        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        // quand l'inscription se passe correctement je renvoie l'utilisateur sur home
        return $this->redirectToRoute('home');
    }

    //Route cr??er pour la page apr??s un enregistrement utilisateur
    #[Route('/register_confirmation/', name: 'register_confirmation', methods: ['GET'])]
    public function showRegisterConfirmation(): Response
    {
        return $this->render('registration/register_confirmation_page.html.twig');
    }

}

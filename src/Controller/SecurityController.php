<?php

namespace App\Controller;

use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\User;
use App\Form\UserType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $encoder
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function registration(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer) {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        // Si les informations sont soumises et valides
        if($form->isSubmitted() && $form->isValid()) {
            // Avant d'envoyer les infos, on encrypte le mdp en utilisant $user
            // qui est une instance de la Classe App\Entity\User déclarée sur le security.yaml
            // avec un algorythm en bcrypt
            $hash = $encoder->encodePassword($user, $user->getPassword());

            // Je modifie le mdp par un hash
            $user->setPassword($hash);

            // Je fais persister
            $entityManager->persist($user);
            // J'envoie en BDD
            $entityManager->flush();

            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $email = $user->getEmail();

            $message = (new \Swift_Message('Bienvenue sur Find My Dev !'))
                ->setFrom('hello.findmydev@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'emails/email_registration.html.twig', [
                        'firstName' => $firstName,
                        'lastName' => $lastName,
                        'email' => $email,
                    ]),
                    'text/html'
                );
            $mailer->send($message);

            $this->addFlash('success', 'Votre profil a bien été créé, connectez-vous à votre compte.');
            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // Récupère l'erreur s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // dernier élément entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}

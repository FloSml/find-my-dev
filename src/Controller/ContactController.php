<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param Request $request
     * @param Swift_Mailer $mailer
     * @return Response
     */
    public function contact(Request $request, \Swift_Mailer $mailer): Response
    {
        $contact = new Contact();
        $contactForm = $this->createForm(ContactType::class, $contact);

        if ($request->isMethod('Post')) {

            $contactForm->handleRequest($request);
            if ($contactForm->isSubmitted() && $contactForm->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();

                $firstName = $contact->getFirstName();
                $lastName = $contact->getLastName();
                $email = $contact->getEmail();
                $contactMessage = $contact->getMessage();

                $message = (new \Swift_Message('Vous avez un nouveau message du contact'))
                    ->setFrom('hello.findmydev@gmail.com')
                    ->setTo('florian.soumaille@lapiscine.pro')
                    ->setBody(
                        $this->renderView(
                            'emails/email_contact.html.twig', [
                                'firstName' => $firstName,
                                'lastName' => $lastName,
                                'email' => $email,
                                'contactMessage' => $contactMessage,
                            ]),
                        'text/html'
                    );
                $mailer->send($message);
                $this->addFlash('contact-success', 'Votre message a bien été envoyé, vous serez recontacté dans les plus brefs délais.');
                return $this->redirectToRoute('contact');
            }
        }
        return $this->render('contact.html.twig', [
            'current_menu' => 'contact',
            'contactForm' => $contactForm->createView(),
        ]);
    }
}

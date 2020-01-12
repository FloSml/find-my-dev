<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function contact(Request $request): Response
    {
        $contact = new Contact();
        $contactForm = $this->createForm(ContactType::class, $contact);

        if ($request->isMethod('Post')) {

            $contactForm->handleRequest($request);
            if ($contactForm->isSubmitted() && $contactForm->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($contact);
                $entityManager->flush();
            }
            $this->addFlash('contact-success', 'Votre message a bien été envoyé, vous serez recontacté dans les plus brefs délais.');
            return $this->redirectToRoute('contact');
        }
        $contactFormView = $contactForm->createView();
        return $this->render('contact.html.twig', [
            'current_menu' => 'contact',
            'contactFormView' => $contactFormView,
        ]);
    }
}

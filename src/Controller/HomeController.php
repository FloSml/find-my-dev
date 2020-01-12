<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $UserRepository;

    /**
     * @Route("/", name="index")
     * @param UserRepository $userRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // J'utilise le repository de User pour sélectionner tous les éléments de ma table user
        // Les repositories permettent de faire les requêtes SELECT dans les tables de la BDD
        $user = $userRepository->findAll();
        $newsletter = new Newsletter();
        $newsletterForm = $this->createForm(NewsletterType::class, $newsletter);

        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $newsletterForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis)
            if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
                $this->addFlash('news-success', 'Vous êtes bien abonné(e), vous recevrez bientôt de nos nouvelles !');
                // J'enregistre en BDD ma variable $article
                // qui est remplie avec les données du formulaire
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newsletter);
                $entityManager->flush();
            }
            return $this->redirectToRoute('index', ['_fragment' => 'newsletter']);
        }

            $variable = rand(1,4);
            $newsletterFormView = $newsletterForm->createView();

        return $this->render('index.html.twig', [
            'newsletterFormView' => $newsletterFormView,
            'current_menu' => 'index',
            'variable' => $variable,
            'users' => $user
        ]);
    }
}
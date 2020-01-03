<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function contact(UserRepository $userRepository)
    {
        $user = $userRepository->findAll();

        return $this->render('contact.html.twig', [
            'current_menu' => 'contact',
            'user' => $user,
        ]);
    }
}

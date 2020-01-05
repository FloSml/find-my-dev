<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @param ArticleRepository $articleRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository)
    {
        $user = $userRepository->findAll();
        $article = $articleRepository->findAll();

        return $this->render('admin/admin_pannel.html.twig', [
            'current_menu' => 'admin',
            'controller_name' => 'AdminController',
            'articles' => $article,
            'users' => $user
        ]);
    }

}

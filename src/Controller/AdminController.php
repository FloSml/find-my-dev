<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @param ArticleRepository $articleRepository
     * @param UserRepository $userRepository
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            5
        );
        $article = $articleRepository->findAll();
        $category = $categoryRepository->findAll();

        return $this->render('admin/admin_pannel.html.twig', [
            'current_menu' => 'admin',
            'controller_name' => 'AdminController',
            'users' => $user,
            'articles' => $article,
            'categories' => $category
        ]);
    }

}

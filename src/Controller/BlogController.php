<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog", methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();
        $article = $articleRepository->findAll();

        return $this->render('blog/blog.html.twig', [
            'current_menu' => 'blog',
            'categories' => $category,
            'articles' => $article,
        ]);
    }

    /**
     * @Route("/blog/newest-articles", name="articles_newest")
     * @param ArticleRepository $articleRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function articleNewest(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $article = $paginator->paginate(
            $articleRepository->findByNewArticles(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('blog/blog.html.twig', [
            'current_menu' => 'members',
            'articles' => $article,
        ]);
    }

    /**
     * @Route("/blog/oldest-articles", name="articles_oldest")
     * @param ArticleRepository $articleRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function articlesOldest(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $article = $paginator->paginate(
            $articleRepository->findByOldArticles(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('blog/blog.html.twig', [
            'current_menu' => 'members',
            'articles' => $article,
        ]);
    }
}

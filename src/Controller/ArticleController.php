<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    /**
     * @Route("/blog", name="blog", methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @param CategoryRepository $categoryRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $category = $categoryRepository->findAll();
        $article = $paginator->paginate(
            $articleRepository->findAll(),
            $request->query->getInt('page', 1),
            6
        );

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

    /**
     * @Route("/article/{id}", name="article_show", methods={"GET"})
     * @param Article $article
     * @return Response
     */
    public function show(Article $article): Response
    {
        return $this->render('blog/article_show.html.twig', [
            'current_menu' => 'article',
            'article' => $article,
        ]);
    }

    /**
     * @Route("admin/article/new", name="admin_article_new", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // je crée un nouvel Article en créant une nouvelle instance de l'entité Article
        $article = new Article();

        $message = "";

        // J'utilise la méthode createForm pour créer le gabarit de
        // formulaire pour l'article : ArticleType
        // et je lui associe mon entité article vide
        $articleForm = $this->createForm(ArticleType::class, $article);

        // Si je suis sur une méthode POST, donc qu'un formulaire a été envoyé
        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $articleForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis)
            if ($articleForm->isSubmitted() && $articleForm->isValid()) {
                $message = "L'article a bien été ajouté/modifié !";
                // J'enregistre en BDD ma variable $article
                // qui est remplie avec les données du formulaire
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($article);
                $entityManager->flush();
        }
            $this->addFlash('success', 'L\'article a bien été créé');
            return $this->redirectToRoute('admin');
        }

        // à partir de mon gabarit, je crée la vue de mon formulaire
        $articleFormView = $articleForm->createView();
        return $this->render('admin/blog/article_new.html.twig', [
            'articles' => $article,
            'articleFormView' => $articleFormView,
        ]);
    }

    /**
     * @Route("admin/article/update/{id}", name="admin_article_update", methods={"GET","POST"})
     * @param ArticleRepository $articleRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param $id
     * @return Response
     */
    public function articleUpdate(ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, $id)
    {
        $article = $articleRepository->find($id);
        $message= "";
        $articleForm = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod('Post')) {

            $articleForm->handleRequest($request);
            if ($articleForm->isValid()) {
                $entityManager->persist($article);
                $entityManager->flush();
            }
            $this->addFlash('success', 'L\'article a bien été modifié');
            return $this->redirectToRoute('admin');
        }
        $articleFormView = $articleForm->createView();
        return $this->render('admin/blog/article_update.html.twig', [
            'articleFormView' => $articleFormView,
            'message' => $message,
        ]);
    }

    /**
     * @Route("admin/article/delete/{id}", name="admin_article_delete", methods={"GET","POST"})
     * @param ArticleRepository $articleRepository
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return Response
     */
    public function articleDelete(ArticleRepository $articleRepository, EntityManagerInterface $entityManager, $id)
    {
        // Je récupère un enregistrement en BDD grâce au Repository de Article
        // $article = Entité Article
        $article = $articleRepository->find($id);

        // J'utilise l'entityManager avec la méthode remove pour enregistrer la suppression du user
        $entityManager->remove($article);
        // Je valide la suppression en BDD avec la méthode flush()
        $entityManager->flush();

        $this->addFlash('success', 'L\'article a bien été supprimé');

        return $this->redirectToRoute('admin');
    }
}

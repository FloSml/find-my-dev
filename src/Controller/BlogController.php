<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("/blog")
 */
// On étend la classe AbstractController pour bénéficier des méthodes et propriétés
// de cette classe dans notre contrôleur
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog", methods={"GET"})
     * @param BlogRepository $blogRepository
     * @return Response
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="blog_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_show", methods={"GET"})
     * @param Blog $blog
     * @return Response
     */
    public function show(Blog $blog): Response
    {
        return $this->render('blog/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="blog_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Blog $blog
     * @return Response
     */
    public function edit(Request $request, Blog $blog): Response
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="blog_delete", methods={"DELETE"})
     * @param Request $request
     * @param Blog $blog
     * @return Response
     */
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_index');
    }
}

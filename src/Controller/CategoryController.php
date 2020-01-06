<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/new", name="admin_category_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category_show');
        }

        return $this->render('admin/category/category_new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_category_update", methods={"GET","POST"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function categoryUpdate(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_category_show');
        }

        return $this->render('admin/category/category_update.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_category_delete", methods={"DELETE"})
     * @param Request $request
     * @param Category $category
     * @return Response
     */
    public function categoryDelete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/show", name="admin_category_show", methods={"GET","POST"})
     * @param Request $request
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function categoryShow(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findAll();

        return $this->render('admin/category/category_show.html.twig', [
            'categories' => $category,
        ]);
    }
}

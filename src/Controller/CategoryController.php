<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

            $this->addFlash('success', 'La catégorie a bien été créée');
            return $this->redirectToRoute('admin');
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

            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/category/category_update.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
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

    /**
     * @Route("/delete/{id}", name="admin_category_delete", methods={"GET","POST"})
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return RedirectResponse
     */
    public function categoryDelete(CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, $id)
    {
        $category = $categoryRepository->find($id);
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'La catégorie a bien été supprimée');
        return $this->redirectToRoute('admin');
    }
}

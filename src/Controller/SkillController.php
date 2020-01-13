<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/skill")
 */
class SkillController extends AbstractController
{
    /**
     * @Route("/new", name="admin_skill_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function skillNew(Request $request): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($skill);
            $entityManager->flush();

            $this->addFlash('success', 'La catégorie a bien été créée');
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/skill/skill_new.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="admin_category_update", methods={"GET","POST"})
     * @param Request $request
     * @param Skill $skill
     * @return Response
     */
    public function skillUpdate(Request $request, Skill $skill): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bien été modifiée');
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/skill/skill_update.html.twig', [
            'skill' => $skill,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_category_delete", methods={"GET","POST"})
     * @param SkillRepository $skillRepository
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return RedirectResponse
     */
    public function skillDelete(SkillRepository $skillRepository, EntityManagerInterface $entityManager, $id)
    {
        $skill = $skillRepository->find($id);
        $entityManager->remove($skill);
        $entityManager->flush();

        $this->addFlash('success', 'La compétence a bien été supprimée');
        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/show", name="admin_skill_show", methods={"GET","POST"})
     * @param Request $request
     * @param SkillRepository $skillRepository
     * @return Response
     */
    public function skillShow(Request $request, SkillRepository $skillRepository): Response
    {
        $skill = $skillRepository->findAll();

        return $this->render('admin/skill/skill_show.html.twig', [
            'skills' => $skill,
        ]);
    }
}

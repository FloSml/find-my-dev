<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository)
    {
        // J'utilise le repository de User pour sélectionner tous les éléments de ma table user
        // Les repositories permettent de faire les requêtes SELECT dans les tables de la BDD
        $user = $userRepository->findAll();

        $variable = rand(1,4);

        return $this->render('index.html.twig', [
            'current_menu' => 'index',
            'variable' => $variable,
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members", name="members")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function members(UserRepository $userRepository)
    {
        $user = $userRepository->findAll();

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/job", name="members_job")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function membersLookingForJob(UserRepository $userRepository)
    {
        $user = $userRepository->findAllLookingForJob();

        return $this->render('members.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/experience/asc", name="members_experience_asc")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function membersExperienceAsc(UserRepository $userRepository)
    {
        $user = $userRepository->findAllExperienceAsc();

        return $this->render('members.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/experience/desc", name="members_experience_desc")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function membersExperienceDesc(UserRepository $userRepository)
    {
        $user = $userRepository->findAllExperienceDesc();

        return $this->render('members.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/search/", name="members_search")
     * @param UserRepository $userRepository
     * @param Request $request
     * @return Response
     *
     * Affiche les membres qui correspondent aux données transmises via le formulaire de recherche (en GET)
     */
    public function membersByCity(UserRepository $userRepository, Request $request)
    {
        $get = [];
        $search = $request->query->get('search-member');
        $get['search-member'] = $search;
        $user = $userRepository->findMember($search);

        return $this->render('members.html.twig', [
            'users' => $user,
            'get' => $get
        ]);
    }

    /**
     * @Route("/member/update/{id}", name="update_member")
     * @param UserRepository $userRepository
     * @param $id
     * @return Response
     */
    public function memberUpdate(UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);

        return $this->render('member.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/member/{id}", name="member")
     * @param UserRepository $userRepository
     * @param $id
     * @return Response
     */
    public function member(UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);

        return $this->render('member.html.twig', [
            'user' => $user,
        ]);
    }
}
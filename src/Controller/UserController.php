<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
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
        $user = $userRepository->findAll();

        return $this->render('index.html.twig', [
            'users' => $user,
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function contact(UserRepository $userRepository)
    {
        $user = $userRepository->findAll();

        return $this->render('contact.html.twig', [
            'user' => $user,
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


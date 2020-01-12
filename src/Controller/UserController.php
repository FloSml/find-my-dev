<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// J'étends la classe AbstractController pour bénéficier des méthodes et propriétés
// de cette classe dans mon contrôleur
class UserController extends AbstractController
{
    private $UserRepository;

    /**
     * @Route("/members", name="members")
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function members(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findAllMembersQuery(),
            $request->query->getInt('page', 1),
            12
        );

        $variable = rand(1,4);

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'variable' => $variable,
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/job", name="members_job")
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function membersLookingForJob(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findAllLookingForJob(),
            $request->query->getInt('page', 1),
            12
        );

        $variable = rand(1,4);

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'variable' => $variable,
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/experience/asc", name="members_experience_asc")
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function membersExperienceAsc(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findAllExperienceAsc(),
            $request->query->getInt('page', 1),
            12
        );

        $variable = rand(1,4);

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'variable' => $variable,
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/experience/desc", name="members_experience_desc")
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function membersExperienceDesc(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findAllExperienceDesc(),
            $request->query->getInt('page', 1),
            12
        );

        $variable = rand(1,4);

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'variable' => $variable,
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/newest-members", name="members_newest")
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function membersNewest(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findByNewMembers(),
            $request->query->getInt('page', 1),
            12
        );

        $variable = rand(1,4);

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'variable' => $variable,
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/oldest-members", name="members_oldest")
     * @param UserRepository $userRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function membersOldest(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findByOldMembers(),
            $request->query->getInt('page', 1),
            12
        );

        $variable = rand(1,4);

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'variable' => $variable,
            'users' => $user,
        ]);
    }

    /**
     * @Route("/members/search/", name="members_search")
     * @param UserRepository $userRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     *
     * Affiche les membres qui correspondent aux données transmises via le formulaire de recherche (en GET)
     */
    public function membersByCity(UserRepository $userRepository, Request $request, PaginatorInterface $paginator)
    {
        $get = [];
        $search = $request->query->get('search-member');
        $get['search-member'] = $search;

        $user = $paginator->paginate(
            $userRepository->findMember($search),
            $request->query->getInt('page', 1),
            12
        );

        $variable = rand(1,4);

        return $this->render('members.html.twig', [
            'current_menu' => 'members',
            'variable' => $variable,
            'users' => $user,
            'get' => $get
        ]);
    }

    /**
     * @Route("/member/update", name="update_member")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function memberUpdate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = "";
        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Unauthorized"
            ], 403);
        }
        $userForm = $this->createForm(UserType::class, $user);

        if ($request->isMethod('Post')) {

            $userForm->handleRequest($request);
            if ($userForm->isValid()) {
                $message = "Votre profil a bien été modifié !";
                $entityManager->persist($user);
                $entityManager->flush();
            }

            return $this->redirectToRoute('members');
        }
        $userFormView = $userForm->createView();
        return $this->render('security/member_update_profile.html.twig', [
            'userFormView' => $userFormView,
            'users' => $user,
            'message' => $message,
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
        $variable = rand(1,4);

        return $this->render('member.html.twig', [
            'current_menu' => 'member',
            'variable' => $variable,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile", name="member_profile")
     * @return Response
     */
    public function profile(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Unauthorized"
            ], 403);
        }

        return $this->render('security/member_profile.html.twig', [
            'current_menu' => 'member',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile/update", name="update_profile")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function profileUpdate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                'code' => 403,
                'message' => "Unauthorized"
            ], 403);
        }
        $userForm = $this->createForm(UserType::class, $user);

        if ($request->isMethod('Post')) {

            $userForm->handleRequest($request);
            if ($userForm->isValid()) {
                $entityManager->persist($user);
                $entityManager->flush();
            }
            $this->addFlash('success', 'Votre profil a bien été mis à jour');
            return $this->redirectToRoute('members');
        }
        $userFormView = $userForm->createView();
        return $this->render('security/member_update_profile.html.twig', [
            'userFormView' => $userFormView,
            'users' => $user,
        ]);

    }

}
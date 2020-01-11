<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\User;
use App\Form\NewsletterType;
use App\Form\UserType;
use App\Repository\NewsletterRepository;
use App\Repository\UserRepository;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @Route("/", name="index")
     * @param UserRepository $userRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // J'utilise le repository de User pour sélectionner tous les éléments de ma table user
        // Les repositories permettent de faire les requêtes SELECT dans les tables de la BDD
        $user = $userRepository->findAll();
        $newsletter = new Newsletter();
        $newsletterForm = $this->createForm(NewsletterType::class, $newsletter);

        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $newsletterForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis)
            if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
                $this->addFlash('news-success', 'Vous êtes bien abonné(e), vous recevrez bientôt de nos nouvelles !');
                // J'enregistre en BDD ma variable $article
                // qui est remplie avec les données du formulaire
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($newsletter);
                $entityManager->flush();
            }
            return $this->redirectToRoute('index', ['_fragment' => 'newsletter']);
        }

            $variable = rand(1,4);
            $newsletterFormView = $newsletterForm->createView();

        return $this->render('index.html.twig', [
            'newsletterFormView' => $newsletterFormView,
            'current_menu' => 'index',
            'variable' => $variable,
            'users' => $user
        ]);
    }

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
     * @Route("/member/update/{id}", name="update_member")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param $id
     * @return Response
     */
    public function memberUpdate(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, $id): Response
    {
        $message = "";
        $user = $userRepository->find($id);
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
     * @Route("/profile/{id}", name="member_profile")
     * @param UserRepository $userRepository
     * @param $id
     * @return Response
     */
    public function profile(UserRepository $userRepository, $id)
    {
        $user = $userRepository->find($id);

        return $this->render('member_profile.html.twig', [
            'current_menu' => 'member',
            'user' => $user,
        ]);
    }

}
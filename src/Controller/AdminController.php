<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\SkillRepository;
use App\Repository\UserRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Material\ColumnChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @param ArticleRepository $articleRepository
     * @param UserRepository $userRepository
     * @param CategoryRepository $categoryRepository
     * @param SkillRepository $skillRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, UserRepository $userRepository, CategoryRepository $categoryRepository, SkillRepository $skillRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $paginator->paginate(
            $userRepository->findAll(),
            $request->query->getInt('page', 1),
            6
        );
        $article = $articleRepository->findAll();
        $category = $categoryRepository->findAll();
        $skill = $paginator->paginate(
            $skillRepository->findAll(),
            $request->query->getInt('pages', 1),
            6
        );

        $homme = count($userRepository->findMen());
        $femme = count($userRepository->findWomen());

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [
                ['Sexe', 'Répartition des membres'],
                ['Hommes', $homme],
                ['Femmes', $femme],
            ]
        );
        $pieChart->getOptions()->setPieSliceText('label');
        $pieChart->getOptions()->setTitle('Répartition des membres par sexe');
        $pieChart->getOptions()->getTitleTextStyle()->setBold(false);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#757575');
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Roboto');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(16);
        $pieChart->getOptions()->setPieStartAngle(100);
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(450);
        $pieChart->getOptions()->setColors(['#5a398e', '#ff6f61']);
        $pieChart->getOptions()->getLegend()->setPosition('top');

        $experienceZeroMen = count($userRepository->findNoExperienceMen());
        $experienceZeroWomen = count($userRepository->findNoExperienceWomen());
        $experienceOneMen = count($userRepository->findOneExperienceMen());
        $experienceOneWomen = count($userRepository->findOneExperienceWomen());
        $experienceTwoMen = count($userRepository->findTwoExperienceMen());
        $experienceTwoWomen = count($userRepository->findTwoExperienceWomen());
        $experienceThreeMen = count($userRepository->findThreeExperienceMen());
        $experienceThreeWomen = count($userRepository->findThreeExperienceWomen());
        $experienceFourMen = count($userRepository->findFourExperienceMen());
        $experienceFourWomen = count($userRepository->findFourExperienceWomen());
        $experienceFiveMen = count($userRepository->findFiveExperienceMen());
        $experienceFiveWomen = count($userRepository->findFiveExperienceWomen());
        $experienceMaxMen = count($userRepository->findMaxExperienceMen());
        $experienceMaxWomen = count($userRepository->findMaxExperienceWomen());

        $chart = new ColumnChart();
        $chart->getData()->setArrayToDataTable([
            ['Expérience', 'Hommes', 'Femmes'],
            ['< 1 an', $experienceZeroMen, $experienceZeroWomen],
            ['1 an', $experienceOneMen, $experienceOneWomen],
            ['2 ans', $experienceTwoMen, $experienceTwoWomen],
            ['3 ans', $experienceThreeMen, $experienceThreeWomen],
            ['4 ans', $experienceFourMen, $experienceFourWomen],
            ['5 ans', $experienceFiveMen, $experienceFiveWomen],
            ['+ 5 ans', $experienceMaxMen, $experienceMaxWomen],
        ]);

        $chart->getOptions()->getChart()
            ->setTitle('Répartition des membres par expérience et par sexe');
        $chart->getOptions()
            ->setBars('vertical')
            ->setHeight(400)
            ->setWidth(400)
            ->setColors(['#5a398e', '#ff6f61'])
            ->getVAxis()
            ->setFormat('decimal');
        $chart->getOptions()->getLegend()->setPosition('none');

        return $this->render('admin/admin_pannel.html.twig', [
            'current_menu' => 'admin',
            'controller_name' => 'AdminController',
            'users' => $user,
            'articles' => $article,
            'categories' => $category,
            'skills' => $skill,
            'piechart' => $pieChart,
            'chart' => $chart
        ]);
    }

}

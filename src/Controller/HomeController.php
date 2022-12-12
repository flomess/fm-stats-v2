<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\NewTeamFormType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        //TODO: redirect to login
        return $this->redirectToRoute('home_teams');
    }

    /**
     * @Route("/teams", name="home_teams")
     */
    public function homeTeams(TeamRepository $teamRepository): Response
    {
        $teams = $teamRepository->findAll();
        return $this->render('index.html.twig', [
            'teams' => $teams,
        ]);
    }

    /**
     * @Route("/teams/new-team", name="new_team")
     */
    public function addTeam(EntityManagerInterface $entityManager, Request $request): Response
    {
        $form = $this->createForm(NewTeamFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $team = new Team();
            $team->setName($form->get('name')->getData());
            $team->setUser($this->getUser());
            if ($form->get('logo')->getData() !== null) {
                $logo = uniqid() . '.' . $form->get('logo')->getData()->guessExtension();
                $team->setLogo($logo);
                $directoryPath = $this->getParameter('kernel.project_dir') . '/public/uploads/logo/';
                $form->get('logo')->getData()->move($directoryPath, $logo);
            }
            $entityManager->persist($team);
            $entityManager->flush();
            return $this->redirectToRoute('home_teams');
        }
        return $this->render('data/new-team.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
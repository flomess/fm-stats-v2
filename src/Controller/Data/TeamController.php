<?php

namespace App\Controller\Data;

use App\Form\NewPlayerFormType;
use App\Form\NewTeamSeasonFormType;
use App\Repository\PlayerRepository;
use App\Repository\TeamRepository;
use App\Repository\TeamSeasonRepository;
use App\Service\AssociateCountry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/team/{teamId}")
 */
class TeamController extends AbstractController
{

    /**
     * @Route("/", name="team_home")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, TeamRepository $teamRepository, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $players = $team->getPlayers();
        $seasons = $team->getTeamSeasons();

        // new season form
        $formSeason = $this->createForm(NewTeamSeasonFormType::class);
        $formSeason->handleRequest($request);
        $showSeasonForm = 'hidden';
        if ($formSeason->isSubmitted() && $formSeason->isValid()) {
            $season = $formSeason->getData();
            foreach ($seasons as $teamSeason) {
                if ($teamSeason->getName() == $formSeason->getData()->getName()) {
                    $this->addFlash('error', 'Cette saison existe déjà');
                    $showSeasonForm = '';
                    return $this->render('data/index.html.twig', [
                        'controller_name' => 'TeamController',
                        'players' => $players,
                        'team' => $team,
                        'seasons' => $seasons,
                        'formSeason' => $formSeason->createView(),
                        'showSeasonForm' => $showSeasonForm,
                    ]);
                }
            }
            $season->setTeam($team);
            $season->setName($formSeason->get('name')->getData());
            $entityManager->persist($season);
            $entityManager->flush();
            $formSeason = $this->createForm(NewTeamSeasonFormType::class);
            $this->redirectToRoute('team_home', ['teamId' => $teamId]);
        }

        return $this->render('data/index.html.twig', [
            'controller_name' => 'TeamController',
            'players' => $players,
            'team' => $team,
            'seasons' => $seasons,
            'formSeason' => $formSeason->createView(),
            'showSeasonForm' => $showSeasonForm,
        ]);
    }

    /**
     * @Route("/new-player", name="new_player")
     */
    public function newPlayer(Request $request, EntityManagerInterface $entityManager, AssociateCountry $associateCountry, TeamRepository $teamRepository, TeamSeasonRepository $teamSeasonRepository, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // new player form
        $form = $this->createForm(NewPlayerFormType::class, null, ['countries' => $associateCountry->getCountryList(), 'clubCountries' => $associateCountry->getCountriesForClub(), 'years' => $teamSeasonRepository->getYears($team)]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $player = $form->getData();
            $player->setTeam($team);
            $arrival = [
                "type" => $form->get('arrival_type')->getData(),
                "date" => $form->get('arrival_date')->getData(),
                "winter" => $form->get('arrival_winter')->getData()
            ];

            if ($form->get('arrival_team')->getData() != '') {
                $arrival["team"] = $form->get('arrival_team')->getData();
                $arrival["country"] = $form->get('arrival_team_country')->getData();
                $arrival["division"] = $form->get('arrival_team_division')->getData();
            }

            if ($arrival["type"] == 'transfert' || $arrival["type"] == 'p.o.a.') {
                $arrival["amount"] = $form->get('arrival_amount')->getData();
            }

            $player->setArrival($arrival);
            $player->setCountryFlag($associateCountry->associateCountryForFlag($form->get('country')->getData()));
            $entityManager->persist($player);
            $entityManager->flush();

            return $this->redirectToRoute('player_home', ['teamId' => $teamId, 'playerId' => $player->getId()]);
        }

        return $this->render('data/new-player.html.twig', [
            'controller_name' => 'TeamController',
            'form' => $form->createView(),
            'team' => $team,
        ]);
    }
}
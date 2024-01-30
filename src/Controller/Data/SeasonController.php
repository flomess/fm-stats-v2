<?php

namespace App\Controller\Data;

use App\Entity\PlayerSeason;
use App\Form\NewCompetitionFormType;
use App\Form\NewPlayerSeasonFormType;
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
 * @Route("/team/{teamId}/season/{seasonId}")
 */
class SeasonController extends AbstractController
{
    /**
     * @Route("/", name="team_season_home")
     */
    public function showSeason(Request $request, EntityManagerInterface $entityManager, TeamRepository $teamRepository, TeamSeasonRepository $teamSeasonRepository, $seasonId, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $season = $teamSeasonRepository->find($seasonId);
        $players = $season->getPlayerSeasons();
        $teamSeasons = $teamSeasonRepository->findByTeam($season->getTeam()->getId());

        $loaned = $season->getPlayerSeasons()->filter(function ($playerSeason) {
            return $playerSeason->isLoaned(true);
        });

        $previousSeason = null;
        foreach ($teamSeasons as $teamSeason) {
            if ($teamSeason->getId() < $seasonId && ($previousSeason == null || $teamSeason->getId() > $previousSeason->getId())) {
                $previousSeason = $teamSeason;
            }
        }

        $nextSeason = null;
        foreach ($teamSeasons as $teamSeason) {
            if ($teamSeason->getId() > $seasonId && ($nextSeason == null || $teamSeason->getId() < $nextSeason->getId())) {
                $nextSeason = $teamSeason;
            }
        }

        $formCompetition = $this->createForm(NewCompetitionFormType::class);
        $formCompetition->handleRequest($request);
        $showCompetitionForm = 'hidden';
        if ($formCompetition->isSubmitted() && $formCompetition->isValid()) {
            $currentCompetitions = $season->getCompetitions();
            if ($currentCompetitions != null) {
                foreach ($currentCompetitions as $currentCompetition) {
                    if ($currentCompetition['name'] == $formCompetition->get('name')->getData()) {
                        $this->addFlash('error', 'Cette compétition existe déjà');
                        $showCompetitionForm = '';
                        return $this->render('data/season/season.html.twig', [
                            'controller_name' => 'TeamController',
                            'season' => $season,
                            'players' => $players,
                            'previousSeason' => $previousSeason,
                            'nextSeason' => $nextSeason,
                            'teamSeasons' => $teamSeasons,
                            'showCompetitionForm' => $showCompetitionForm,
                            'formCompetition' => $formCompetition->createView(),
                        ]);
                    }
                }
            }
            $newCompetition = [
                'name' => $formCompetition->get('name')->getData(),
                'ranking' => $formCompetition->get('ranking')->getData(),
                'matches' => $formCompetition->get('matches')->getData(),
                'victories' => $formCompetition->get('victories')->getData(),
                'draws' => $formCompetition->get('draws')->getData(),
                'defeats' => $formCompetition->get('defeats')->getData(),
                'g' => $formCompetition->get('g')->getData(),
                'ga' => $formCompetition->get('ga')->getData(),
            ];
            $currentCompetitions[] = $newCompetition;
            $season->setCompetitions($currentCompetitions);
            $entityManager->persist($season);
            $entityManager->flush();
            $formCompetition = $this->createForm(NewCompetitionFormType::class);
            $this->redirectToRoute('team_season_home', ['teamId' => $season->getTeam()->getId(), 'seasonId' => $season->getId()]);
        }

        return $this->render('data/season/season.html.twig', [
            'controller_name' => 'TeamController',
            'season' => $season,
            'players' => $players,
            'previousSeason' => $previousSeason,
            'nextSeason' => $nextSeason,
            'teamSeasons' => $teamSeasons,
            'showCompetitionForm' => $showCompetitionForm,
            'formCompetition' => $formCompetition->createView(),
            'loaned' => $loaned
        ]);
    }

    /**
     * @Route("/new-player", name="new_season_player")
     */
    public function newSeasonPlayer(Request $request, EntityManagerInterface $entityManager, TeamRepository $teamRepository, TeamSeasonRepository $teamSeasonRepository, AssociateCountry $associateCountry, $seasonId, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $teamSeason = $teamSeasonRepository->find($seasonId);

        $season = new PlayerSeason();
        $form = $this->createForm(NewPlayerSeasonFormType::class, $season, ['players' => $teamRepository->getCurrentPlayers($team), 'clubCountries' => $associateCountry->getCountriesForClub()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $season = $form->getData();
            $season->setTeamSeason($teamSeason);
            $season->setIsLoaned($form->get('is_loaned')->getData());
            $stats = [
                "matches" => $form->get('matches')->getData(),
                "first_team" => $form->get('first_team')->getData(),
                "goals" => $form->get('goals')->getData(),
                "assists" => $form->get('assists')->getData(),
                "yellow_cards" => $form->get('yellow_cards')->getData(),
                "red_cards" => $form->get('red_cards')->getData(),
                "rate" => $form->get('rate')->getData(),
            ];
            $season->setStats($stats);
            if ($form->get('is_loaned')->getData()) {
                $loan_infos = [
                    'team' => $form->get('loan_team')->getData(),
                    'division' => $form->get('loan_team_division')->getData(),
                    'country' => $form->get('loan_team_country')->getData(),
                    'half_season' => $form->get('half_season')->getData()
                ];
                $season->setLoanInfos($loan_infos);
            }

            $entityManager->persist($season);
            $entityManager->flush();
            return $this->redirectToRoute('team_season_home', ['teamId' => $teamId, 'seasonId' => $teamSeason->getId()]);
        }

        return $this->render('data/season/new-season-player.html.twig', [
            'controller_name' => 'TeamController',
            'form' => $form->createView(),
            'team' => $team,
            'season' => $teamSeason,
        ]);
    }

    /**
     * @Route("/mercato", name="season_mercato")
     */
    public function seasonMercato(Request $request, EntityManagerInterface $entityManager, TeamRepository $teamRepository, TeamSeasonRepository $teamSeasonRepository, AssociateCountry $associateCountry, PlayerRepository $playerRepository, $seasonId, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $season = $teamSeasonRepository->find($seasonId);
        $players = $season->getPlayerSeasons();
        $teamSeasons = $teamSeasonRepository->findByTeam($season->getTeam()->getId());
        $seasonYear = substr($season->getName(), 0, 4);


        $previousSeason = null;
        foreach ($teamSeasons as $teamSeason) {
            if ($teamSeason->getId() < $seasonId && ($previousSeason == null || $teamSeason->getId() > $previousSeason->getId())) {
                $previousSeason = $teamSeason;
            }
        }

        $nextSeason = null;
        foreach ($teamSeasons as $teamSeason) {
            if ($teamSeason->getId() > $seasonId && ($nextSeason == null || $teamSeason->getId() < $nextSeason->getId())) {
                $nextSeason = $teamSeason;
            }
        }

        // get players with arrival date = season year
        $players = $team->getPlayers();
        $arrivals = [];
        $departures = [];
        foreach ($players as $player) {
            if (($player->getArrival()['date'] == $seasonYear && $player->getArrival()['winter'] != true)
            || ($player->getArrival()['date'] == $seasonYear + 1 && $player->getArrival()['winter'] == true)){
                $arrivals[] = $player;
            }

            if ($player->getDeparture() && (($player->getDeparture()['date'] == $seasonYear && $player->getDeparture()['winter'] != true)
            || ($player->getDeparture()['date'] == $seasonYear + 1 && $player->getDeparture()['winter'] == true))){
                $departures[] = $player;
            }
        }

        // sort arrivals by id, but those who arrived in winter are last
        usort($arrivals, function ($a, $b) {
            if ($a->getArrival()['winter'] == true && $b->getArrival()['winter'] == false) {
                return 1;
            } else if ($a->getArrival()['winter'] == false && $b->getArrival()['winter'] == true) {
                return -1;
            } else {
                return $a->getId() - $b->getId();
            }
        });

        // sort departures by id, but those who left in winter are last
        usort($departures, function ($a, $b) {
            if ($a->getDeparture()['winter'] == true && $b->getDeparture()['winter'] == false) {
                return 1;
            } else if ($a->getDeparture()['winter'] == false && $b->getDeparture()['winter'] == true) {
                return -1;
            } else {
                return $a->getId() - $b->getId();
            }
        });

        return $this->render('data/season/mercato.html.twig', [
            'controller_name' => 'TeamController',
            'arrivals' => $arrivals,
            'departures' => $departures,
            'team' => $team,
            'season' => $season,
            'previousSeason' => $previousSeason,
            'nextSeason' => $nextSeason,
            'teamSeasons' => $teamSeasons,
        ]);
    }
}
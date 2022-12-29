<?php

namespace App\Controller\Data;

use App\Form\PlayerDepartureFormType;
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
 * @Route("/team/{teamId}/player/{playerId}")
 */
class PlayerController extends AbstractController
{
    /**
     * @Route("/", name="player_home")
     */
    public function showPlayer(Request $request, EntityManagerInterface $entityManager, TeamRepository $teamRepository, TeamSeasonRepository $teamSeasonRepository, PlayerRepository $playerRepository, AssociateCountry $associateCountry, $playerId, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $player = $playerRepository->find($playerId);
        $showDepartureForm = 'hidden';

        $form = $this->createForm(PlayerDepartureFormType::class, null, ['clubCountries' => $associateCountry->getCountriesForClub(), 'years' => $teamSeasonRepository->getYears($team)]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $departure = [
                "type" => $form->get('departure_type')->getData(),
                "date" => $form->get('departure_date')->getData(),
                "winter" => $form->get('departure_winter')->getData(),
                "team" => $form->get('departure_team')->getData(),
                "country" => $form->get('departure_team_country')->getData(),
                "division" => $form->get('departure_team_division')->getData(),
                "amount" => $form->get('departure_amount')->getData(),
            ];

            $player->setDeparture($departure);
            $entityManager->persist($player);
            $entityManager->flush();
            $form = $this->createForm(PlayerDepartureFormType::class);
            return $this->redirectToRoute('player_home', ['teamId' => $teamId, 'playerId' => $playerId]);
        }

        return $this->render('data/player.html.twig', [
            'controller_name' => 'TeamController',
            'player' => $player,
            'form' => $form->createView(),
            'showDepartureForm' => $showDepartureForm,
        ]);
    }
}
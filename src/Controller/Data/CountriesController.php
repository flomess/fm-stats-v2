<?php

namespace App\Controller\Data;

use App\Repository\TeamRepository;
use App\Service\AssociateCountry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("team/{teamId}/countries")
 */
class CountriesController extends AbstractController
{
    /**
     * @Route("/", name="countries_home")
     */
    public function index(TeamRepository $teamRepository, AssociateCountry $associateCountry, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $countries[] = ['country' => 'France', 'players' => [], 'flag' => 'fr', 'nb_matches' => 0];
        foreach ($team->getPlayers() as $player) {
            $found = false;
            $nb_matches = 0;
            foreach($player->getPlayerSeasons() as $playerSeason){
                $nb_matches += $playerSeason->getStats()['matches'];
            }
            // player data to add
            $player_values = ["name" => $player->getFirstname().' '.$player->getLastname(),
                            "position" => $player->getPosition(),
                            "nb_matches" => $nb_matches,
                            "arrival" => $player->getArrival(),
                            "departure" => $player->getDeparture()
                        ];
            foreach ($countries as $key => $country) {
                // add player to country
                if ($country['country'] === $player->getCountry()) {
                    $countries[$key]['players'][] = $player_values;
                    $countries[$key]['nb_matches'] += $nb_matches;
                    $found = true;
                }
            }
            // add new country
            if ($found === false) {
                $countries[] = ['country' => $player->getCountry(), 'players' => [$player_values], 'flag' => $player->getCountryFlag(), 'nb_matches' => $nb_matches];
            }
        }

        // order by nb matches
        usort($countries, function($a, $b){
            return $b['nb_matches'] <=> $a['nb_matches'];
        });

        return $this->render('data/countries/countries.html.twig', [
            'countries' => $countries,
            'team' => $team
        ]);;
    }
}
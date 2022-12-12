<?php

namespace App\Controller\Data;

use App\Repository\TeamRepository;
use App\Service\AssociateCountry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("team/{teamId}/map")
 */
class MapController extends AbstractController
{
    /**
     * @Route("/", name="map_home")
     */
    public function index(TeamRepository $teamRepository, AssociateCountry $associateCountry, $teamId): Response
    {
        // check if user has rights on team
        $team = $teamRepository->find($teamId);
        if ($team->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $countries[] = ['country' => 'France', 'players' => '0'];
        foreach ($team->getPlayers() as $player) {
            $found = false;
            foreach ($countries as $key => $country) {
                $playerCountry = $associateCountry->associateCountryForMap($player->getCountry());
                if ($country['country'] === $playerCountry) {
                    $countries[$key]['players'] = $country['players'] + 1;
                    $found = true;
                }
            }
            if ($found === false) {
                $countries[] = ['country' => $playerCountry, 'players' => '1'];
            }
        }

        return $this->render('data/map/map.html.twig', [
            'countries' => $countries,
            'team' => $team
        ]);;
    }
}
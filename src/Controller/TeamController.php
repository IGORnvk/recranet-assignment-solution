<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\SeasonRepository;
use App\Repository\TeamRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeamController extends AbstractController
{
    #[Route('/teams/{year}', name: 'teams')]
    public function index(SeasonRepository $seasonRepository, string $year): Response
    {
        $season = $seasonRepository->findOneBy(['year' => $year]);

        return $this->render('team/index.html.twig', [
            'teams' => $season->getSeasonTeams(),
            'year' => $year
        ]);
    }

    #[Route('/teams/{year}/{team}', name: 'team_details')]
    public function show(SeasonRepository $seasonRepository, TeamRepository $teamRepository, GameRepository $gameRepository, Request $request, string $year, int $team): Response
    {
        $season = $seasonRepository->findOneBy(['year' => $year]);
        $teamName = $teamRepository->findOneBy(['id' => $team])->getName();
        $games = $gameRepository->findByTeamId($team, $season->getId());

        // create pagination
        $adapter = new QueryAdapter($games);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );

        return $this->render('team/show.html.twig', [
            'games' => $games,
            'year' => $year,
            'team' => $teamName,
            'pager' => $pagerfanta
        ]);
    }
}

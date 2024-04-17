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
    public function index(GameRepository $gameRepository, SeasonRepository $seasonRepository, string $year): Response
    {
        $season = $seasonRepository->findOneBy(['year' => $year]);

        // retrieve the latest games for followed teams by the user
        $teams = $this->getUser()->getTeam();
        $latestGames = $gameRepository->getLatestGamesForTeams($teams, $season) ?: 'not available';

        return $this->render('team/index.html.twig', [
            'teams' => $season->getSeasonTeams(),
            'year' => $year,
            'latestGames' => $latestGames
        ]);
    }

    #[Route('/teams/{year}/{teamId}', name: 'team_details')]
    public function show(SeasonRepository $seasonRepository, TeamRepository $teamRepository, GameRepository $gameRepository, Request $request, string $year, int $teamId): Response
    {
        $season = $seasonRepository->findOneBy(['year' => $year]);
        $team = $teamRepository->find($teamId);

        // create pagination
        $games = $gameRepository->findByTeamId($teamId, $season->getId());
        $adapter = new QueryAdapter($games);
        $pagerfanta = Pagerfanta::createForCurrentPageWithMaxPerPage(
            $adapter,
            $request->query->get('page', 1),
            9
        );

        return $this->render('team/show.html.twig', [
            'year' => $year,
            'team' => $team,
            'pager' => $pagerfanta
        ]);
    }

    #[Route('/team/follow/{follow}/{teamId}', name: 'team_user')]
    public function updateUserTeam(TeamRepository $teamRepository, $follow, int $teamId): Response
    {
        $team = $teamRepository->find($teamId);
        $user = $this->getUser();

        $teamRepository->updateUserRelation($user, $team, $follow);

        return $this->redirectToRoute('teams', ['year' => date('Y') - 1]);
    }
}

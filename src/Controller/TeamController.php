<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
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
}

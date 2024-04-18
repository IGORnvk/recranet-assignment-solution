<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
use App\Repository\SeasonTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(SeasonRepository $seasonRepository): Response
    {
        $seasons = $seasonRepository->findBy([], ['year' => 'ASC']);

        return $this->render('home/index.html.twig', [
            'seasons' => $seasons
        ]);
    }
}

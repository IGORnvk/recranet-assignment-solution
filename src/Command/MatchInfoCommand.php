<?php

namespace App\Command;

use App\Repository\GameRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'matches-info',
    description: 'Updates information about Eredivisie matches',
)]
class MatchInfoCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client,
        private string $league,
        private RouterInterface $router,
        private GameRepository $gameRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Updates information about Eredivisie matches')
            ->setHelp('This command allows you to update data about Eredivisie matches and their results based on the season')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year of the season to get matches data from')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $year = $input->getArgument('year') ? $input->getArgument('year') : date('Y') - 1;

        // generate path to a route
        $path = $this->router->generate('football_data_matches', [
            'league' => $this->league,
            'season' => $year
        ]);

        // make a request and process response
        $response = $this->client->request('GET', $path)->toArray();

        $matches = $response['matches'];

        foreach ($matches as $match) {
            // prepare data
            $homeTeamName = $match['homeTeam']['name'];
            $guestTeamName = $match['awayTeam']['name'];
            $score = $match['score']['fullTime'];
            $date = $match['utcDate'];
            $status = $match['status'];
            $referee = reset($match['referees']) ? reset($match['referees']) : [];

            $this->gameRepository->updateGame($homeTeamName, $guestTeamName, $date, $status, $year, $referee, $score);
        }

        $io->success('Matches were successfully updated!');

        return Command::SUCCESS;
    }
}

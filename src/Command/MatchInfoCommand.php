<?php

namespace App\Command;

use App\Repository\GameRepository;
use App\Repository\SeasonRepository;
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
        private GameRepository $gameRepository,
        private SeasonRepository $seasonRepository
    )
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
        $year = $input->getArgument('year') ?: date('Y') - 1;

        // validate year
        if (!in_array($year, range(date('Y') - 4, date('Y') - 1))) {
            $io->error('Specified season is not available. Available seasons: [' .
                implode(", ", range(date('Y') - 4, date('Y') - 1)). '].');

            return Command::FAILURE;
        }

        if (!$this->seasonRepository->findBy(['year' => $year])) {
            $io->error("Teams for the specified season are not updated. Run 'teams-info' first.");

            return Command::FAILURE;
        }

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

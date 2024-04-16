<?php

namespace App\Command;

use App\Repository\TeamRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'teams-info',
    description: 'Updates information about Eredivisie teams',
)]
class TeamInfoCommand extends Command
{
    private HttpClientInterface $client;

    private RouterInterface $router;

    private TeamRepository $teamRepository;

    private string $league;

    public function __construct(HttpClientInterface $client, string $league, RouterInterface $router, TeamRepository $teamRepository)
    {
        $this->client = $client;
        $this->league = $league;
        $this->router = $router;
        $this->teamRepository = $teamRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Updates information about Eredivisie teams')
            ->setHelp('This command allows you to update data about Eredivisie teams and their standings for a particular season')
            ->addArgument('year', InputArgument::OPTIONAL, 'Year of the season to get teams data from')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $year = $input->getArgument('year') ? $input->getArgument('year') : date('Y') - 1;

        // generate path to a route
        $path = $this->router->generate('football_data_standings', [
            'league' => $this->league,
            'season' => $year
        ]);

        // make a request and process response
        $response = $this->client->request('GET', $path)->toArray();

        $standings = $response['standings'][0]['table'];

        foreach ($standings as $standing) {
            $teamInfo = $standing['team'];
            $position = $standing['position'];

            // update data of the team
            $this->teamRepository->updateTeam($teamInfo, $position, $standing, $year);
        }

        $io->success('Teams were successfully updated!');

        return Command::SUCCESS;
    }
}

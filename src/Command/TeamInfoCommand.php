<?php

namespace App\Command;

use App\Entity\Statistic;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;
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
    description: 'Retrieves information about Eredivisie teams',
)]
class TeamInfoCommand extends Command
{
    private HttpClientInterface $client;

    private RouterInterface $router;

    private EntityManagerInterface $entityManager;

    private string $league;

    public function __construct(HttpClientInterface $client, string $league, RouterInterface $router, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->league = $league;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Retrieves information about Eredivisie teams')
            ->setHelp('This command allows you to get data about Eredivisie teams and their standings')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // generate path to a route
        $path = $this->router->generate('football_data_standings', [
            'league' => $this->league,
        ]);

        // make a request and process response
        $response = $this->client->request('GET', $path)->toArray();
        $standings = $response['standings'][0]['table'];

        foreach ($standings as $standing) {
            // create new objects for team and its statistic
            $team = new Team();
            $statistic = new Statistic();

            // set all the necessary values for team
            $team->setPosition($standing['position']);

            $teamInfo = $standing['team'];

            $team
                ->setName($teamInfo['name'])
                ->setLogo($teamInfo['crest'])
            ;

            // set all the necessary values for statistic
            $statistic
                ->setTeam($team)
                ->setPlayed($standing['playedGames'])
                ->setWon($standing['won'])
                ->setLost($standing['lost'])
                ->setDraw($standing['draw'])
                ->setGoalDifference($standing['goalDifference'])
            ;

            $this->entityManager->persist($team);
            $this->entityManager->persist($statistic);

            $this->entityManager->flush();
        }

        $io->success('Teams were successfully updated!');

        return Command::SUCCESS;
    }
}

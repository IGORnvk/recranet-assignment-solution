<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'matches-info',
    description: 'Retrieves information about Eredivisie matches',
)]
class MatchInfoCommand extends Command
{
    private HttpClientInterface $client;

    private RouterInterface $router;

    private string $league;

    public function __construct(HttpClientInterface $client, RouterInterface $router, string $league)
    {
        $this->client = $client;
        $this->router = $router;
        $this->league = $league;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Retrieves information about Eredivisie matches')
            ->setHelp('This command allows you to get data about Eredivisie matches and their results')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $path = $this->router->generate('football_data_matches', [
            'league' => $this->league,
            'escape' => 'javascript'
        ]);

        $response = $this->client->request('GET', $path);
        $response = $response->toArray();
        $matches = $response['matches'];

        foreach ($matches as $match) {
            dd($match);
        }

        $io->success('Data was successfully updated!');

        return Command::FAILURE;
    }
}

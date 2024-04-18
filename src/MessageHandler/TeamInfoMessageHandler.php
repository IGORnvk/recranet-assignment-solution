<?php

namespace App\MessageHandler;

use App\Message\TeamInfoMessage;
use App\Repository\TeamRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final class TeamInfoMessageHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private RouterInterface $router,
        private TeamRepository $teamRepository)
    {
    }

    public function __invoke(TeamInfoMessage $message)
    {
        // generate path to a route
        $path = $this->router->generate('football_data_standings', [
            'league' => $message->getLeague(),
        ]);

        // make a request and process response
        $response = $this->client->request('GET', $path)->toArray();

        $standings = $response['standings'][0]['table'];

        foreach ($standings as $standing) {
            $teamInfo = $standing['team'];
            $position = $standing['position'];

            // update data of the team
            $this->teamRepository->updateTeam($teamInfo, $position, $standing, date('Y') - 1);
        }

        $this->logger->notice('Teams were successfully updated!');
    }
}

<?php

namespace App\MessageHandler;

use App\Message\MatchInfoMessage;
use App\Repository\GameRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final class MatchInfoMessageHandler
{
    public function __construct(
        private HttpClientInterface $client,
        private LoggerInterface $logger,
        private RouterInterface $router,
        private GameRepository $gameRepository)
    {
    }
    public function __invoke(MatchInfoMessage $message)
    {
        // generate path to a route
        $path = $this->router->generate('football_data_matches', [
            'league' => $message->getLeague(),
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
            $referee = reset($match['referees']) ?: [];

            $this->gameRepository->updateGame($homeTeamName, $guestTeamName, $date, $status, date('Y') - 1, $referee, $score);
        }

        $this->logger->notice('Matches were successfully updated!');
    }
}

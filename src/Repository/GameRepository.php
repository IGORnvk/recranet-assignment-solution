<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    private TeamRepository $teamRepository;

    private SeasonRepository $seasonRepository;

    private RefereeRepository $refereeRepository;

    private ScoreRepository $scoreRepository;

    public function __construct(ManagerRegistry $registry, TeamRepository $teamRepository, SeasonRepository $seasonRepository, RefereeRepository $refereeRepository, ScoreRepository $scoreRepository)
    {
        $this->teamRepository = $teamRepository;
        $this->seasonRepository = $seasonRepository;
        $this->refereeRepository = $refereeRepository;
        $this->scoreRepository = $scoreRepository;

        parent::__construct($registry, Game::class);
    }

    /**
     * creates or updates current state of the game
     * @param string $homeTeamName name of the home team
     * @param string $guestTeamName name of the guest team
     * @param string $date date of the match
     * @param string $status status of the match
     * @param string $year season
     * @param array $refereeInfo information about referee
     * @param array $scoreInfo information about score
     * @return void
     */
    public function updateGame(string $homeTeamName, string $guestTeamName, string $date, string $status, string $year, array $refereeInfo, array $scoreInfo): void
    {
        $entityManager = $this->getEntityManager();

        // retrieve both home and guest teams
        $homeTeam = $this->teamRepository->findOneBy(['name' => $homeTeamName]);
        $guestTeam = $this->teamRepository->findOneBy(['name' => $guestTeamName]);

        // retrieve season
        $season = $this->seasonRepository->findOneBy(['year' => $year]);

        // create new object for game or retrieve existing one
        $game = $this->findOneBy(['home_team' => $homeTeam->getId(), 'guest_team' => $guestTeam->getId(),
                'season' => $season->getId()]) ?
            $this->findOneBy(['home_team' => $homeTeam->getId(), 'guest_team' => $guestTeam->getId(),
                'season' => $season->getId()]) :
            new Game();

        // set all the necessary values
        $game
            ->setHomeTeam($homeTeam)
            ->setGuestTeam($guestTeam)
            ->setSeason($season)
            ->setDate(DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $date))
            ->setStatus($status)
        ;

        if (!empty($refereeInfo)) {
            // retrieve and set referee
            $referee = $this->refereeRepository->updateReferee($refereeInfo);
            $game->setReferee($referee);
        }

        if ($status == 'FINISHED') {
            // update score
            $this->scoreRepository->updateScore($scoreInfo, $game);
        }

        $entityManager->persist($game);
        $entityManager->flush();
    }

    /**
     * retrieves the latest game(s) of the particular season for every team in the collection
     * @param Collection $teams teams to retrieve game(s) from
     * @param Season $season season to retrieve a game from
     * @return array array of the latest game(s)
     */
    public function getLatestGamesForTeams(Collection $teams, Season $season): array
    {
        $games = [];
        foreach ($teams as $team) {
            $latestGame = $this->findByTeamId($team->getId(), $season->getId())
                ->andWhere("g.status = 'FINISHED'")
                ->setMaxResults(1)
                ->getQuery()
                ->execute();
            if ($latestGame && !in_array($latestGame[0], $games)) {
                $games[] = reset($latestGame);
            }
        }

        return $games;
    }

    /**
     * retrieves games for a particular team in a particular season
     * @return QueryBuilder returns an array of Game objects
     */
    public function findByTeamId(int $teamId, int $seasonId): QueryBuilder
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.season = :season_id')
            ->andWhere('g.home_team = :team_id OR g.guest_team = :team_id')
            ->setParameter('season_id', $seasonId)
            ->setParameter('team_id', $teamId)
            ->orderBy('g.date', 'DESC')
        ;
    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

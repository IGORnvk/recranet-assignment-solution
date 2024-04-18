<?php

namespace App\Repository;

use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Season>
 *
 * @method Season|null find($id, $lockMode = null, $lockVersion = null)
 * @method Season|null findOneBy(array $criteria, array $orderBy = null)
 * @method Season[]    findAll()
 * @method Season[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    /**
     * inserts unique season into the database
     * @param string $year year to be inserted
     * @return void
     */
    public function insertSeason(string $year): void
    {
        $entityManager = $this->getEntityManager();

        // insert season if it doesn't exist already
        if(!$this->findOneBy(['year' => $year])) {
            $season = new Season();

            $season->setYear($year);

            $entityManager->persist($season);
            $entityManager->flush();
        }
    }

//    /**
//     * @return Season[] Returns an array of Season objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Season
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

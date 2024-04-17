<?php

namespace App\Entity;

use App\Repository\StatisticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticRepository::class)]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $played = null;

    #[ORM\Column]
    private ?int $won = null;

    #[ORM\Column]
    private ?int $lost = null;

    #[ORM\Column]
    private ?int $draw = null;

    #[ORM\Column]
    private ?int $goal_difference = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\OneToOne(inversedBy: 'statistic', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SeasonTeam $season_team = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayed(): ?int
    {
        return $this->played;
    }

    public function setPlayed(int $played): static
    {
        $this->played = $played;

        return $this;
    }

    public function getWon(): ?int
    {
        return $this->won;
    }

    public function setWon(int $won): static
    {
        $this->won = $won;

        return $this;
    }

    public function getLost(): ?int
    {
        return $this->lost;
    }

    public function setLost(int $lost): static
    {
        $this->lost = $lost;

        return $this;
    }

    public function getDraw(): ?int
    {
        return $this->draw;
    }

    public function setDraw(int $draw): static
    {
        $this->draw = $draw;

        return $this;
    }

    public function getGoalDifference(): ?int
    {
        return $this->goal_difference;
    }

    public function setGoalDifference(int $goal_difference): static
    {
        $this->goal_difference = $goal_difference;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    public function getSeasonTeam(): ?SeasonTeam
    {
        return $this->season_team;
    }

    public function setSeasonTeam(SeasonTeam $season_team): static
    {
        $this->season_team = $season_team;

        return $this;
    }
}

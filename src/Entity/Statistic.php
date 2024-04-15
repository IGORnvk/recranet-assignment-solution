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

    #[ORM\OneToOne(inversedBy: 'statistic', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team_id = null;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeamId(): ?Team
    {
        return $this->team_id;
    }

    public function setTeamId(Team $team_id): static
    {
        $this->team_id = $team_id;

        return $this;
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
}

<?php

namespace App\Entity;

use App\Repository\SeasonTeamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonTeamRepository::class)]
class SeasonTeam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'seasonTeams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

    #[ORM\ManyToOne(inversedBy: 'seasonTeams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\OneToOne(mappedBy: 'season_team', cascade: ['persist', 'remove'])]
    private ?Statistic $statistic = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getStatistic(): ?Statistic
    {
        return $this->statistic;
    }

    public function setStatistic(Statistic $statistic): static
    {
        // set the owning side of the relation if necessary
        if ($statistic->getSeasonTeam() !== $this) {
            $statistic->setSeasonTeam($this);
        }

        $this->statistic = $statistic;

        return $this;
    }
}

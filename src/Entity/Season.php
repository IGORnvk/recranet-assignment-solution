<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 4)]
    private ?string $year = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'season', orphanRemoval: true)]
    private Collection $games;

    /**
     * @var Collection<int, SeasonTeam>
     */
    #[ORM\OneToMany(targetEntity: SeasonTeam::class, mappedBy: 'season', orphanRemoval: true)]
    private Collection $seasonTeams;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->seasonTeams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): static
    {
        if (!$this->games->contains($game)) {
            $this->games->add($game);
            $game->setSeason($this);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getSeason() === $this) {
                $game->setSeason(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SeasonTeam>
     */
    public function getSeasonTeams(): Collection
    {
        return $this->seasonTeams;
    }

    public function addSeasonTeam(SeasonTeam $seasonTeam): static
    {
        if (!$this->seasonTeams->contains($seasonTeam)) {
            $this->seasonTeams->add($seasonTeam);
            $seasonTeam->setSeason($this);
        }

        return $this;
    }

    public function removeSeasonTeam(SeasonTeam $seasonTeam): static
    {
        if ($this->seasonTeams->removeElement($seasonTeam)) {
            // set the owning side to null (unless already changed)
            if ($seasonTeam->getSeason() === $this) {
                $seasonTeam->setSeason(null);
            }
        }

        return $this;
    }
}

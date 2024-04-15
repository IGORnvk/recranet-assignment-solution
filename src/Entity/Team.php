<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $logo = null;

    #[ORM\Column]
    private ?int $position = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'home_team_id', orphanRemoval: true)]
    private Collection $homeGames;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\OneToMany(targetEntity: Game::class, mappedBy: 'guest_team_id', orphanRemoval: true)]
    private Collection $guestGames;

    #[ORM\OneToOne(mappedBy: 'team_id', cascade: ['persist', 'remove'])]
    private ?Statistic $statistic = null;

    public function __construct()
    {
        $this->homeGames = new ArrayCollection();
        $this->guestGames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

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

    /**
     * @return Collection<int, Game>
     */
    public function getHomeGames(): Collection
    {
        return $this->homeGames;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGuestGames(): Collection
    {
        return $this->guestGames;
    }

    public function addHomeGame(Game $game): static
    {
        if (!$this->homeGames->contains($game)) {
            $this->homeGames->add($game);
            $game->setHomeTeamId($this);
        }

        return $this;
    }

    public function removeHomeGame(Game $game): static
    {
        if ($this->homeGames->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getHomeTeamId() === $this) {
                $game->setHomeTeamId(null);
            }
        }

        return $this;
    }

    public function addGuestGame(Game $game): static
    {
        if (!$this->guestGames->contains($game)) {
            $this->guestGames->add($game);
            $game->setGuestTeamId($this);
        }

        return $this;
    }

    public function removeGuestGame(Game $game): static
    {
        if ($this->guestGames->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getGuestTeamId() === $this) {
                $game->setGuestTeamId(null);
            }
        }

        return $this;
    }

    public function getStatistic(): ?Statistic
    {
        return $this->statistic;
    }

    public function setStatistic(Statistic $statistic): static
    {
        // set the owning side of the relation if necessary
        if ($statistic->getTeamId() !== $this) {
            $statistic->setTeamId($this);
        }

        $this->statistic = $statistic;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'homeGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $home_team_id = null;

    #[ORM\ManyToOne(inversedBy: 'guestGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $guest_team_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Referee $referee_id = null;

    #[ORM\OneToOne(mappedBy: 'game_id', cascade: ['persist', 'remove'])]
    private ?Score $score = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getHomeTeamId(): ?Team
    {
        return $this->home_team_id;
    }

    public function setHomeTeamId(?Team $home_team_id): static
    {
        $this->home_team_id = $home_team_id;

        return $this;
    }

    public function getGuestTeamId(): ?Team
    {
        return $this->guest_team_id;
    }

    public function setGuestTeamId(?Team $guest_team_id): static
    {
        $this->guest_team_id = $guest_team_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getRefereeId(): ?Referee
    {
        return $this->referee_id;
    }

    public function setRefereeId(?Referee $referee_id): static
    {
        $this->referee_id = $referee_id;

        return $this;
    }

    public function getScore(): ?Score
    {
        return $this->score;
    }

    public function setScore(Score $score): static
    {
        // set the owning side of the relation if necessary
        if ($score->getGameId() !== $this) {
            $score->setGameId($this);
        }

        $this->score = $score;

        return $this;
    }
}

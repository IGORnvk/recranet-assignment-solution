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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $home_team = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $guest_team = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Referee $referee = null;

    #[ORM\OneToOne(mappedBy: 'game', cascade: ['persist', 'remove'])]
    private ?Score $score = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

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

    public function getHomeTeam(): ?Team
    {
        return $this->home_team;
    }

    public function setHomeTeam(?Team $home_team): static
    {
        $this->home_team = $home_team;

        return $this;
    }

    public function getGuestTeam(): ?Team
    {
        return $this->guest_team;
    }

    public function setGuestTeam(?Team $guest_team): static
    {
        $this->guest_team = $guest_team;

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

    public function getReferee(): ?Referee
    {
        return $this->referee;
    }

    public function setReferee(?Referee $referee): static
    {
        $this->referee = $referee;

        return $this;
    }

    public function getScore(): ?Score
    {
        return $this->score;
    }

    public function setScore(Score $score): static
    {
        // set the owning side of the relation if necessary
        if ($score->getGame() !== $this) {
            $score->setGame($this);
        }

        $this->score = $score;

        return $this;
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
}

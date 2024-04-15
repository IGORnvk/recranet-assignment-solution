<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'score', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column]
    private ?int $home_score = null;

    #[ORM\Column]
    private ?int $guest_score = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->home_score;
    }

    public function setHomeScore(int $home_score): static
    {
        $this->home_score = $home_score;

        return $this;
    }

    public function getGuestScore(): ?int
    {
        return $this->guest_score;
    }

    public function setGuestScore(int $guest_score): static
    {
        $this->guest_score = $guest_score;

        return $this;
    }
}

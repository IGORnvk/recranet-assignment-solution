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

    /**
     * @var Collection<int, SeasonTeam>
     */
    #[ORM\OneToMany(targetEntity: SeasonTeam::class, mappedBy: 'team', orphanRemoval: true)]
    private Collection $seasonTeams;

    public function __construct()
    {
        $this->seasonTeams = new ArrayCollection();
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
            $seasonTeam->setTeam($this);
        }

        return $this;
    }

    public function removeSeasonTeam(SeasonTeam $seasonTeam): static
    {
        if ($this->seasonTeams->removeElement($seasonTeam)) {
            // set the owning side to null (unless already changed)
            if ($seasonTeam->getTeam() === $this) {
                $seasonTeam->setTeam(null);
            }
        }

        return $this;
    }
}

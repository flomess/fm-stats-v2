<?php

namespace App\Entity;

use App\Repository\TeamSeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamSeasonRepository::class)
 */
class TeamSeason
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="teamSeasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $competitions = [];

    /**
     * @ORM\OneToMany(targetEntity=PlayerSeason::class, mappedBy="teamSeason")
     */
    private $playerSeasons;

    public function __construct()
    {
        $this->playerSeasons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getCompetitions(): ?array
    {
        return $this->competitions;
    }

    public function setCompetitions(?array $competitions): self
    {
        $this->competitions = $competitions;

        return $this;
    }

    /**
     * @return Collection<int, PlayerSeason>
     */
    public function getPlayerSeasons(): Collection
    {
        return $this->playerSeasons;
    }

    public function addPlayerSeason(PlayerSeason $playerSeason): self
    {
        if (!$this->playerSeasons->contains($playerSeason)) {
            $this->playerSeasons[] = $playerSeason;
            $playerSeason->setTeamSeason($this);
        }

        return $this;
    }

    public function removePlayerSeason(PlayerSeason $playerSeason): self
    {
        if ($this->playerSeasons->removeElement($playerSeason)) {
            // set the owning side to null (unless already changed)
            if ($playerSeason->getTeamSeason() === $this) {
                $playerSeason->setTeamSeason(null);
            }
        }

        return $this;
    }
}

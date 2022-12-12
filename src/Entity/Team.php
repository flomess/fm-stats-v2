<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="teams")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Player::class, mappedBy="team")
     */
    private $players;

    /**
     * @ORM\OneToMany(targetEntity=TeamSeason::class, mappedBy="team")
     */
    private $teamSeasons;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->teamSeasons = new ArrayCollection();
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setTeam($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getTeam() === $this) {
                $player->setTeam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TeamSeason>
     */
    public function getTeamSeasons(): Collection
    {
        return $this->teamSeasons;
    }

    public function addTeamSeason(TeamSeason $teamSeason): self
    {
        if (!$this->teamSeasons->contains($teamSeason)) {
            $this->teamSeasons[] = $teamSeason;
            $teamSeason->setTeam($this);
        }

        return $this;
    }

    public function removeTeamSeason(TeamSeason $teamSeason): self
    {
        if ($this->teamSeasons->removeElement($teamSeason)) {
            // set the owning side to null (unless already changed)
            if ($teamSeason->getTeam() === $this) {
                $teamSeason->setTeam(null);
            }
        }

        return $this;
    }
}

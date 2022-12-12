<?php

namespace App\Entity;

use App\Repository\PlayerSeasonRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerSeasonRepository::class)
 */
class PlayerSeason
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="playerSeasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity=TeamSeason::class, inversedBy="playerSeasons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $teamSeason;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $stats = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLoaned;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $loanInfos = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getTeamSeason(): ?TeamSeason
    {
        return $this->teamSeason;
    }

    public function setTeamSeason(?TeamSeason $teamSeason): self
    {
        $this->teamSeason = $teamSeason;

        return $this;
    }

    public function getStats(): ?array
    {
        return $this->stats;
    }

    public function setStats(?array $stats): self
    {
        $this->stats = $stats;

        return $this;
    }

    public function isLoaned(): ?bool
    {
        return $this->isLoaned;
    }

    public function setIsLoaned(bool $isLoaned): self
    {
        $this->isLoaned = $isLoaned;

        return $this;
    }

    public function getLoanInfos(): ?array
    {
        return $this->loanInfos;
    }

    public function setLoanInfos(?array $loanInfos): self
    {
        $this->loanInfos = $loanInfos;

        return $this;
    }
}
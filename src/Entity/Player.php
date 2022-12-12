<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class, inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $countryFlag;

    /**
     * @ORM\OneToMany(targetEntity=PlayerSeason::class, mappedBy="player")
     */
    private $playerSeasons;

    /**
     * @ORM\Column(type="json")
     */
    private $arrival = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $departure = null;

    public function __construct()
    {
        $this->playerSeasons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCountryFlag(): ?string
    {
        return $this->countryFlag;
    }

    public function setCountryFlag(string $countryFlag): self
    {
        $this->countryFlag = $countryFlag;

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
            $playerSeason->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerSeason(PlayerSeason $playerSeason): self
    {
        if ($this->playerSeasons->removeElement($playerSeason)) {
            // set the owning side to null (unless already changed)
            if ($playerSeason->getPlayer() === $this) {
                $playerSeason->setPlayer(null);
            }
        }

        return $this;
    }

    public function getArrival(): ?array
    {
        return $this->arrival;
    }

    public function setArrival(array $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    public function getDeparture(): ?array
    {
        return $this->departure;
    }

    public function setDeparture(?array $departure): self
    {
        $this->departure = $departure;

        return $this;
    }
}
<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SpeciesRepository::class)
 * @UniqueEntity( fields={"name"} )
 * @UniqueEntity( fields={"latinName"} )
 */
class Species
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $latinName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $recommendedSubstrate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recommendedSoilMoisture;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recommendedAcidityMin;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recommendedAcidityMax;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $fertilizerNeed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $floweringMonth;

    /**
     * @ORM\ManyToOne(targetEntity=StatusUicn::class)
     */
    private $statusUicn;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recommendedExposure;

    /**
     * @ORM\ManyToOne(targetEntity=LeafType::class, inversedBy="species")
     */
    private $leafType;

    /**
     * @ORM\ManyToMany(targetEntity=Lot::class, mappedBy="species")
     */
    private $lots;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validate = false;

    public function __construct()
    {
        $this->lots = new ArrayCollection();
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

    public function getRecommendedSubstrate(): ?string
    {
        return $this->recommendedSubstrate;
    }

    public function setRecommendedSubstrate(?string $recommendedSubstrate): self
    {
        $this->recommendedSubstrate = $recommendedSubstrate;

        return $this;
    }

    public function getRecommendedSoilMoisture(): ?int
    {
        return $this->recommendedSoilMoisture;
    }

    public function setRecommendedSoilMoisture(?int $recommendedSoilMoisture): self
    {
        $this->recommendedSoilMoisture = $recommendedSoilMoisture;

        return $this;
    }

    public function getFertilizerNeed(): ?int
    {
        return $this->fertilizerNeed;
    }

    public function setFertilizerNeed(?int $fertilizerNeed): self
    {
        $this->fertilizerNeed = $fertilizerNeed;

        return $this;
    }

    public function getFloweringMonth(): ?int
    {
        return $this->floweringMonth;
    }

    public function setFloweringMonth(?int $floweringMonth): self
    {
        $this->floweringMonth = $floweringMonth;

        return $this;
    }

    public function getStatusUicn(): ?StatusUicn
    {
        return $this->statusUicn;
    }

    public function setStatusUicn(?StatusUicn $statusUicn): self
    {
        $this->statusUicn = $statusUicn;

        return $this;
    }

    public function getRecommendedExposure(): ?int
    {
        return $this->recommendedExposure;
    }

    public function setRecommendedExposure(?int $recommendedExposure): self
    {
        $this->recommendedExposure = $recommendedExposure;

        return $this;
    }

    public function getLeafType(): ?LeafType
    {
        return $this->leafType;
    }

    public function setLeafType(?LeafType $leafType): self
    {
        $this->leafType = $leafType;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->getName(), $this->getLatinName());
    }

    public function getLatinName(): ?string
    {
        return $this->latinName;
    }

    public function setLatinName(string $latinName): self
    {
        $this->latinName = $latinName;

        return $this;
    }

    /**
     * @return Collection|Lot[]
     */
    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(Lot $lot): self
    {
        if (!$this->lots->contains($lot)) {
            $this->lots[] = $lot;
            $lot->addSpecies($this);
        }

        return $this;
    }

    public function removeLot(Lot $lot): self
    {
        if ($this->lots->removeElement($lot)) {
            $lot->removeSpecies($this);
        }

        return $this;
    }

    public function getValidate(): ?bool
    {
        return $this->validate;
    }

    public function setValidate(bool $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function getRecommendedAcidityMin(): ?int
    {
        return $this->recommendedAcidityMin;
    }

    public function setRecommendedAcidityMin(?int $recommendedAcidityMin): self
    {
        $this->recommendedAcidityMin = $recommendedAcidityMin;

        return $this;
    }

    public function getRecommendedAcidityMax(): ?int
    {
        return $this->recommendedAcidityMax;
    }

    public function setRecommendedAcidityMax(?int $recommendedAcidityMax): self
    {
        $this->recommendedAcidityMax = $recommendedAcidityMax;

        return $this;
    }
}

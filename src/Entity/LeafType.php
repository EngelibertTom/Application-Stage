<?php

namespace App\Entity;

use App\Repository\LeafTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=LeafTypeRepository::class)
 * @UniqueEntity( fields={"name"} )
 */
class LeafType
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
     * @ORM\OneToMany (targetEntity=Species::class, mappedBy="leafType", cascade={"persist", "remove"})
     */
    private $species;

    public function __construct()
    {
        $this->species = new ArrayCollection();
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

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection|Species[]
     */
    public function getSpecies(): Collection
    {
        return $this->species;
    }

    public function addSpecies(Species $species): self
    {
        if (!$this->species->contains($species)) {
            $this->species[] = $species;
            $species->setLeafType($this);
        }

        return $this;
    }

    public function removeSpecies(Species $species): self
    {
        if ($this->species->removeElement($species)) {
            // set the owning side to null (unless already changed)
            if ($species->getLeafType() === $this) {
                $species->setLeafType(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\GreenhouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={ "nursery_id", "name"}),
 * })
 * @ORM\Entity(repositoryClass=GreenhouseRepository::class)
 * @UniqueEntity(fields={"name", "nursery"})
 */
class Greenhouse
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
     * @ORM\ManyToOne(targetEntity=Nursery::class, inversedBy="greenhouses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nursery;

    /**
     * @ORM\OneToMany(targetEntity=Tree::class, mappedBy="greenhouse")
     */
    private $trees;

    /**
     * @ORM\ManyToMany(targetEntity=CultureTable::class, inversedBy="greenhouses")
     */
    private $cultureTables;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="greenhouses")
     */
    private $location;

    public function __construct()
    {
        $this->trees = new ArrayCollection();
        $this->cultureTables = new ArrayCollection();
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

    public function getNursery(): ?Nursery
    {
        return $this->nursery;
    }

    public function setNursery(?Nursery $nursery): self
    {
        $this->nursery = $nursery;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection|Tree[]
     */
    public function getTrees(): Collection
    {
        return $this->trees;
    }

    public function addTree(Tree $tree): self
    {
        if (!$this->trees->contains($tree)) {
            $this->trees[] = $tree;
            $tree->setGreenhouse($this);
        }

        return $this;
    }

    public function removeTree(Tree $tree): self
    {
        if ($this->trees->removeElement($tree)) {
            // set the owning side to null (unless already changed)
            if ($tree->getGreenhouse() === $this) {
                $tree->setGreenhouse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CultureTable[]
     */
    public function getCultureTables(): Collection
    {
        return $this->cultureTables;
    }

    public function addCultureTable(CultureTable $cultureTable): self
    {
        if (!$this->cultureTables->contains($cultureTable)) {
            $this->cultureTables[] = $cultureTable;
        }

        return $this;
    }

    public function removeCultureTable(CultureTable $cultureTable): self
    {
        $this->cultureTables->removeElement($cultureTable);

        return $this;
    }

    public function getSpecies(): array
    {
        $species = [];

        foreach ($this->getTrees() as $tree)
        {
            if (($specie = $tree->getSpecies()) && !in_array($specie, $species, true))
            {
               $species[] = $specie;
            }
        }

        return $species;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}

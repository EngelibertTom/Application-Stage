<?php

namespace App\Entity;

use App\Repository\CultureTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CultureTableRepository::class)
 * @UniqueEntity(fields={"name"})
 */
class CultureTable
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
     * @ORM\ManyToMany(targetEntity=Greenhouse::class, mappedBy="cultureTables")
     */
    private $greenhouses;

    public function __construct()
    {
        $this->greenhouses = new ArrayCollection();
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
     * @return Collection|Greenhouse[]
     */
    public function getGreenhouses(): Collection
    {
        return $this->greenhouses;
    }

    public function addGreenhouse(Greenhouse $greenhouse): self
    {
        if (!$this->greenhouses->contains($greenhouse)) {
            $this->greenhouses[] = $greenhouse;
            $greenhouse->addCultureTable($this);
        }

        return $this;
    }

    public function removeGreenhouse(Greenhouse $greenhouse): self
    {
        if ($this->greenhouses->removeElement($greenhouse)) {
            $greenhouse->removeCultureTable($this);
        }

        return $this;
    }
}

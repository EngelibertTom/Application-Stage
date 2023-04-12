<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={ "nursery_id", "name"}),
 * })
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location
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
     * @ORM\ManyToOne(targetEntity=Nursery::class, inversedBy="locations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nursery;

    /**
     * @ORM\OneToMany(targetEntity=Greenhouse::class, mappedBy="location")
     */
    private $greenhouses;

    public function __construct()
    {
        $this->trees = new ArrayCollection();
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
            $greenhouse->setLocation($this);
        }

        return $this;
    }

    public function removeGreenhouse(Greenhouse $greenhouse): self
    {
        if ($this->greenhouses->removeElement($greenhouse)) {
            // set the owning side to null (unless already changed)
            if ($greenhouse->getLocation() === $this) {
                $greenhouse->setLocation(null);
            }
        }

        return $this;
    }
}

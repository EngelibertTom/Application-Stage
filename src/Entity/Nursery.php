<?php

namespace App\Entity;

use App\Manager\TreeStatusManager;
use App\Repository\NurseryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=NurseryRepository::class)
 * @UniqueEntity( fields={"name"} )
 */
class Nursery
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
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="nurseries")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=ManagementNursery::class, mappedBy="nursery", orphanRemoval=true)
     */
    private $managementNurseries;

    /**
     * @ORM\OneToMany(targetEntity=Greenhouse::class, mappedBy="nursery", orphanRemoval=true)
     */
    private $greenhouses;

    /**
     * @ORM\OneToMany(targetEntity=Lot::class, mappedBy="nursery", orphanRemoval=true)
     */
    private $lots;

    /**
     * @ORM\OneToMany(targetEntity=Tree::class, mappedBy="nursery", orphanRemoval=true)
     */
    private $trees;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Location::class, mappedBy="nursery", orphanRemoval=true)
     */
    private $locations;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->managementNurseries = new ArrayCollection();
        $this->greenhouses = new ArrayCollection();
        $this->lots = new ArrayCollection();
        $this->trees = new ArrayCollection();
        $this->locations = new ArrayCollection();
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

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addNurseries($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeNurseries($this);
        }

        return $this;
    }

    /**
     * @return Collection|ManagementNursery[]
     */
    public function getManagementNurseries(): Collection
    {
        return $this->managementNurseries;
    }

    public function addManagementNurseries(ManagementNursery $managementNurseries): self
    {
        if (!$this->managementNurseries->contains($managementNurseries)) {
            $this->managementNurseries[] = $managementNurseries;
            $managementNurseries->setNursery($this);
        }

        return $this;
    }

    public function removeManagementNurseries(ManagementNursery $managementNurseries): self
    {
        if ($this->managementNurseries->removeElement($managementNurseries)) {
            // set the owning side to null (unless already changed)
            if ($managementNurseries->getNursery() === $this) {
                $managementNurseries->setNursery(null);
            }
        }

        return $this;
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
            $greenhouse->setNursery($this);
        }

        return $this;
    }

    public function removeGreenhouse(Greenhouse $greenhouse): self
    {
        if ($this->greenhouses->removeElement($greenhouse)) {
            // set the owning side to null (unless already changed)
            if ($greenhouse->getNursery() === $this) {
                $greenhouse->setNursery(null);
            }
        }

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
            $lot->setNursery($this);
        }

        return $this;
    }

    public function removeLot(Lot $lot): self
    {
        if ($this->lots->removeElement($lot)) {
            // set the owning side to null (unless already changed)
            if ($lot->getNursery() === $this) {
                $lot->setNursery(null);
            }
        }

        return $this;
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
            $tree->setNursery($this);
        }

        return $this;
    }

    public function getSponsorTrees(): Collection
    {
        return $this->getTrees()->filter(static function(Tree $tree) {
            return $tree->getStatus() === TreeStatusManager::SPONSORABLE;
        });
    }

    public function getAdoptableTrees(): Collection
    {
        return $this->getTrees()->filter(static function(Tree $tree) {
            return $tree->getStatus() === TreeStatusManager::ADOPTABLE;
        });
    }

    public function removeTree(Tree $tree): self
    {
        if ($this->trees->removeElement($tree)) {
            // set the owning side to null (unless already changed)
            if ($tree->getNursery() === $this) {
                $tree->setNursery(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setNursery($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getNursery() === $this) {
                $location->setNursery(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ManagementNurseryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ManagementNurseryRepository::class)
 */
class ManagementNursery
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="managementNurseries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Nursery::class, inversedBy="managementNurseries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nursery;

    /**
     * @ORM\Column(type="boolean")
     */
    private $defaultNursery;

    public function __construct()
    {
        $this->defaultNursery = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNursery(): ?Nursery
    {
        return $this->nursery;
    }

    public function setNursery(?Nursery $nursery): self
    {
        $this->nursery = $nursery;

        return $this;
    }

    public function getDefaultNursery(): ?bool
    {
        return $this->defaultNursery;
    }

    public function setDefaultNursery(bool $defaultNursery): self
    {
        $this->defaultNursery = $defaultNursery;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\MaintenanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MaintenanceRepository::class)
 */
class Maintenance
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Entretien::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $entretien;

    /**
     * @ORM\Column(type="boolean")
     */
    private $terminer = false;


    /**
     * @ORM\Column(type="integer")
     */
    private $urgence;

    /**
     * @ORM\ManyToOne(targetEntity=Equipement::class, inversedBy="maintenances"  )
     */
    private $equipement;

    public function __construct()
    {
        $this->equipement = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getEntretien(): ?Entretien
    {
        return $this->entretien;
    }

    public function setEntretien(?Entretien $entretien): self
    {
        $this->entretien = $entretien;

        return $this;
    }

    public function getTerminer(): ?bool

    {

        return $this->terminer;
    }

    public function setTerminer(bool $terminer): self
    {
        $this->terminer = $terminer;

        return $this;
    }

    public function getUrgence(): ?int
    {
        return $this->urgence;
    }

    public function setUrgence(int $urgence): self
    {
        $this->urgence = $urgence;

        return $this;
    }

    public function getEquipement(): ?Equipement
    {
        return $this->equipement;
    }

    public function setEquipement(?Equipement $equipement): self
    {
        $this->equipement = $equipement;

        return $this;
    }



}

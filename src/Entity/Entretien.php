<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntretienRepository::class)
 * @ORM\EntityListeners({"App\EventListener\EntretienListener"})
 */
class Entretien
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
    private $dateRealisation;

    /**
     * @ORM\Column(type="text")
     */
    private $Observation;

    /**
     * @ORM\Column(type="integer")
     */
    private $Urgence;

    /**
     * @ORM\Column(type="text")
     */
    private $Utilisateur;


    /**
     * @ORM\Column(type="text")
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @ORM\Column(type="integer")
     */
    private $Planifier;

    /**
     * @ORM\ManyToOne(targetEntity=Equipement::class, inversedBy="entretiens" )
     */
    private $equipement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRealisation(): ?\DateTimeInterface
    {
        return $this->dateRealisation;
    }

    public function setDateRealisation(\DateTimeInterface $dateRealisation): self
    {
        $this->dateRealisation = $dateRealisation;

        return $this;
    }

    public function getObservation(): ?string
    {
        return $this->Observation;
    }

    public function setObservation(string $Observation): self
    {
        $this->Observation = $Observation;

        return $this;
    }

    public function getUrgence(): ?int
    {
        return $this->Urgence;
    }

    public function setUrgence(int $Urgence): self
    {
        $this->Urgence = $Urgence;

        return $this;
    }

    public function getUtilisateur(): ?string
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(string $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }


    public function __toString(): string {



        return $this->getNom();
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getPlanifier(): ?bool
    {
        return $this->Planifier;
    }

    public function setPlanifier(bool $Planifier): self
    {
        $this->Planifier = $Planifier;

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

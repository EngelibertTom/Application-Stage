<?php

namespace App\Entity;

use App\Repository\EquipementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EquipementRepository::class)
 * @ORM\EntityListeners({"App\EventListener\EquipementListener"})
 */
class Equipement extends \App\Entity\QrCode
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
     * @ORM\OneToMany(targetEntity=PretEquipement::class, mappedBy="Equipement", orphanRemoval=true)
     */
    private $pretEquipements;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qrCode;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\OneToMany(targetEntity=Entretien::class, mappedBy="equipement", orphanRemoval=true)
     */
    private $entretiens;

    /**
     * @ORM\OneToMany (targetEntity=Maintenance::class, mappedBy="equipement", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $maintenances;



    public function __construct()
    {
        $this->pretEquipements = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
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

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection|PretEquipement[]
     */
    public function getPretEquipements(): Collection
    {
        return $this->pretEquipements;
    }

    public function addPretEquipement(PretEquipement $pretEquipement): self
    {
        if (!$this->pretEquipements->contains($pretEquipement)) {
            $this->pretEquipements[] = $pretEquipement;
            $pretEquipement->setEquipement($this);
        }

        return $this;
    }

    public function removePretEquipement(PretEquipement $pretEquipement): self
    {
        if ($this->pretEquipements->removeElement($pretEquipement)) {
            // set the owning side to null (unless already changed)
            if ($pretEquipement->getEquipement() === $this) {
                $pretEquipement->setEquipement(null);
            }
        }

        return $this;
    }

    public function __toString(): string {
        return $this->getName();
    }

    public function setQrCode(string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    /**
     * @return Collection|Entretien[]
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): self
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens[] = $entretien;
            $entretien->setEquipement($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): self
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getEquipement() === $this) {
                $entretien->setEquipement(null);
            }
        }

        return $this;
    }

    public function getMaintenances(): Collection
    {
        return $this->maintenances;
    }

    public function setMaintenances(?maintenance $maintenance): self
    {
        $this->maintenances = $maintenance;

        return $this;
    }


}

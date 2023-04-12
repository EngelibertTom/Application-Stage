<?php

namespace App\Entity;

use App\Repository\PretEquipementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PretEquipementRepository::class)
 */
class PretEquipement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $Date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column(type="text")
     */
    private $Utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Equipement::class, inversedBy="pretEquipements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Equipement;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $commentaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

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

    public function getEquipement(): ?equipement
    {
        return $this->Equipement;
    }

    public function setEquipement(?equipement $Equipement): self
    {
        $this->Equipement = $Equipement;

        return $this;
    }



    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }


}




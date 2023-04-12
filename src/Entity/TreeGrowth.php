<?php

namespace App\Entity;

use App\Repository\TreeGrowthRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TreeGrowthRepository::class)
 */
class TreeGrowth
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
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Tree::class, inversedBy="treeGrowths")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tree;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $height;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $nebariDiameter;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $trunkDiameter;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTree(): ?Tree
    {
        return $this->tree;
    }

    public function setTree(?Tree $tree): self
    {
        $this->tree = $tree;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getNebariDiameter(): ?float
    {
        return $this->nebariDiameter;
    }

    public function setNebariDiameter(?float $nebariDiameter): self
    {
        $this->nebariDiameter = $nebariDiameter;

        return $this;
    }

    public function getTrunkDiameter(): ?float
    {
        return $this->trunkDiameter;
    }

    public function setTrunkDiameter(?float $trunkDiameter): self
    {
        $this->trunkDiameter = $trunkDiameter;

        return $this;
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
}

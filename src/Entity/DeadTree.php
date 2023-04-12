<?php

namespace App\Entity;

use App\Repository\DeadTreeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DeadTreeRepository::class)
 * @ORM\EntityListeners({"App\EventListener\DeadTreeListener"})
 */
class DeadTree
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
     * @ORM\OneToOne(targetEntity=Tree::class, inversedBy="deadTree", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tree;

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

    public function getTree(): ?Tree
    {
        return $this->tree;
    }

    public function setTree(Tree $tree): self
    {
        $this->tree = $tree;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\HistoryTreeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoryTreeRepository::class)
 */
class HistoryTree
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tree::class, inversedBy="historyTrees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tree;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visiblePublic;

    /**
     * @ORM\ManyToOne(targetEntity=TypeHistoryTree::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->visiblePublic = false;
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getVisiblePublic(): ?bool
    {
        return $this->visiblePublic;
    }

    public function setVisiblePublic(bool $visiblePublic): self
    {
        $this->visiblePublic = $visiblePublic;

        return $this;
    }

    public function getType(): ?TypeHistoryTree
    {
        return $this->type;
    }

    public function setType(?TypeHistoryTree $type): self
    {
        $this->type = $type;

        return $this;
    }
}

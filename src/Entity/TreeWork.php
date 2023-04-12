<?php

namespace App\Entity;

use App\Repository\TreeWorkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TreeWorkRepository::class)
 * @ORM\EntityListeners({"App\EventListener\TreeWorkListener"})
 */
class TreeWork
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
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Tree::class, inversedBy="works")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tree;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Work::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $work;

    /**
     * @ORM\Column(type="boolean")
     */
    private $todo = false; // Si le travaux est terminée ou pas.

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date ?? new \DateTime();

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

    public function getTree(): ?Tree
    {
        return $this->tree;
    }

    public function setTree(?Tree $tree): self
    {
        $this->tree = $tree;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getWork(): ?Work
    {
        return $this->work;
    }

    public function setWork(?Work $work): self
    {
        $this->work = $work;

        return $this;
    }

    public function getTodo(): ?bool
    {
        return $this->todo;
    }

    public function setTodo(bool $todo): self
    {
        $this->todo = $todo;

        return $this;
    }
}

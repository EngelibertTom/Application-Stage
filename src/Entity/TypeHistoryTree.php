<?php

namespace App\Entity;

use App\Repository\TypeHistoryTreeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeHistoryTreeRepository::class)
 */
class TypeHistoryTree
{
    public const TYPE_WORK = 1;
    public const TYPE_MOVE = 2;
    public const TYPE_CREATE = 3;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $icon;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $classColor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getClassColor(): ?string
    {
        return $this->classColor;
    }

    public function setClassColor(string $classColor): self
    {
        $this->classColor = $classColor;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}

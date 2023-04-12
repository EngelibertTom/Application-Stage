<?php

namespace App\Entity;

use App\Repository\TreePictureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TreePictureRepository::class)
 * @ORM\EntityListeners({"App\EventListener\TreePictureListener"})
 */
class TreePicture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Tree::class, inversedBy="treePictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tree;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="boolean")
     */
    private $featured = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pathOriginal;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }

    public function getPathOriginal(): ?string
    {
        return $this->pathOriginal;
    }

    public function setPathOriginal(?string $pathOriginal): self
    {
        $this->pathOriginal = $pathOriginal;

        return $this;
    }
}

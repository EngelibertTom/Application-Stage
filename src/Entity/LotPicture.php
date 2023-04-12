<?php

namespace App\Entity;

use App\Repository\LotPictureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LotPictureRepository::class)
 */
class LotPicture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Lot::class, inversedBy="lotPictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pathOriginal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): self
    {
        $this->lot = $lot;

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

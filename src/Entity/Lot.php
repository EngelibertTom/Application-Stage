<?php

namespace App\Entity;

use App\Repository\LotRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=LotRepository::class)
 * @ORM\EntityListeners({"App\EventListener\LotListener"})
 */
class Lot extends QrCode
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $qrCode;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $entryDate;

    /**
     * @ORM\OneToMany(targetEntity=Tree::class, mappedBy="lot")
     */
    private $trees;

    /**
     * @ORM\ManyToOne(targetEntity=Nursery::class, inversedBy="lots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nursery;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity=RecoveryType::class)
     */
    private $recoveryType;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $recoveryCause;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ageRecovery;

    /**
     * @ORM\OneToMany(targetEntity=LotPicture::class, mappedBy="lot", orphanRemoval=true)
     */
    private $lotPictures;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Species::class, inversedBy="lots")
     */
    private $species;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    public function __construct()
    {
        $this->trees = new ArrayCollection();
        $this->lotPictures = new ArrayCollection();
        $this->species = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQrCode(): ?string
    {
        return $this->qrCode;
    }

    public function setQrCode(string $qrCode): self
    {
        $this->qrCode = $qrCode;

        return $this;
    }

    public function getEntryDate(): ?\DateTimeInterface
    {
        return $this->entryDate;
    }

    public function setEntryDate(\DateTimeInterface $entryDate): self
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * @return Collection|Tree[]
     */
    public function getTrees(): Collection
    {
        return $this->trees;
    }

    public function addTree(Tree $tree): self
    {
        if (!$this->trees->contains($tree)) {
            $this->trees[] = $tree;
            $tree->setLot($this);
        }

        return $this;
    }

    public function removeTree(Tree $tree): self
    {
        if ($this->trees->removeElement($tree)) {
            // set the owning side to null (unless already changed)
            if ($tree->getLot() === $this) {
                $tree->setLot(null);
            }
        }

        return $this;
    }

    public function getNursery(): ?Nursery
    {
        return $this->nursery;
    }

    public function setNursery(?Nursery $nursery): self
    {
        $this->nursery = $nursery;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getRecoveryType(): ?RecoveryType
    {
        return $this->recoveryType;
    }

    public function setRecoveryType(?RecoveryType $recoveryType): self
    {
        $this->recoveryType = $recoveryType;

        return $this;
    }

    /**
     * @return Collection|LotPicture[]
     */
    public function getLotPictures(): Collection
    {
        return $this->lotPictures;
    }

    public function addLotPicture(LotPicture $lotPicture): self
    {
        if (!$this->lotPictures->contains($lotPicture)) {
            $this->lotPictures[] = $lotPicture;
            $lotPicture->setLot($this);
        }

        return $this;
    }

    public function removeLotPicture(LotPicture $lotPicture): self
    {
        if ($this->lotPictures->removeElement($lotPicture)) {
            // set the owning side to null (unless already changed)
            if ($lotPicture->getLot() === $this) {
                $lotPicture->setLot(null);
            }
        }

        return $this;
    }

    public function getRecoveryCause(): ?string
    {
        return $this->recoveryCause;
    }

    public function setRecoveryCause(?string $recoveryCause): self
    {
        $this->recoveryCause = $recoveryCause;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getAgeRecovery(): ?int
    {
        return $this->ageRecovery;
    }

    public function setAgeRecovery(?int $ageRecovery): self
    {
        $this->ageRecovery = $ageRecovery;

        return $this;
    }

    /**
     * @return Collection|Species[]
     */
    public function getSpecies(): Collection
    {
        return $this->species;
    }

    public function addSpecies(Species $species): self
    {
        if (!$this->species->contains($species)) {
            $this->species[] = $species;
        }

        return $this;
    }

    public function removeSpecies(Species $species): self
    {
        $this->species->removeElement($species);

        return $this;
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

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }
}

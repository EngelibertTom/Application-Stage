<?php

namespace App\Entity;

use App\Repository\OwnerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=OwnerRepository::class)
 * @UniqueEntity(fields={"email"})
 */
class Owner implements UserInterface
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
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Adoption::class, mappedBy="owner", orphanRemoval=true)
     */
    private $adoptions;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array();

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastConnection;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $postalCode;

    public function __construct()
    {
        $this->adoptions = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @return Collection|Adoption[]
     */
    public function getAdoptions(): Collection
    {
        return $this->adoptions;
    }

    public function addAdoption(Adoption $adoption): self
    {
        if (!$this->adoptions->contains($adoption)) {
            $this->adoptions[] = $adoption;
            $adoption->setOwner($this);
        }

        return $this;
    }

    public function removeAdoption(Adoption $adoption): self
    {
        if ($this->adoptions->removeElement($adoption)) {
            // set the owning side to null (unless already changed)
            if ($adoption->getOwner() === $this) {
                $adoption->setOwner(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): self
    {
        $this->plainPassword = $password;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->getName();
    }

    public function eraseCredentials()
    {

    }

    public function getLastConnection(): ?\DateTimeInterface
    {
        return $this->lastConnection;
    }

    public function setLastConnection(?\DateTimeInterface $lastConnection): self
    {
        $this->lastConnection = $lastConnection;

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

    public function getListAdoptionSpecies(): string
    {
        $listSpecies = '';
        $adoptions = $this->getAdoptions();

        foreach ($adoptions as $key => $adoption)
        {
            if ($species = $adoption->getTree()->getSpecies())
            {
                $listSpecies .= $species->getName();

                if ($key < $adoptions->count() - 1)
                {
                    $listSpecies .= ', ';
                }
            }
        }

        return $listSpecies;
    }
}

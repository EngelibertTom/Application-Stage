<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity( fields={"email"} )
 * @method string getUserIdentifier()
 */
class User implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array();

    /**
     * @ORM\ManyToMany(targetEntity=Nursery::class, inversedBy="users")
     */
    private $nurseries;

    /**
     * @ORM\OneToMany(targetEntity=ManagementNursery::class, mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private $managementNurseries;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    public function __construct()
    {
        $this->nurseries = new ArrayCollection();
        $this->managementNurseries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return Collection|Nursery[]
     */
    public function getNurseries(): Collection
    {
        return $this->nurseries;
    }

    public function addNurseries(Nursery $nurseries): self
    {
        if (!$this->nurseries->contains($nurseries)) {
            $this->nurseries[] = $nurseries;
        }

        return $this;
    }

    public function removeNurseries(Nursery $nurseries): self
    {
        $this->nurseries->removeElement($nurseries);

        return $this;
    }

    /**
     * @return Collection|ManagementNursery[]
     */
    public function getManagementNurseries(): Collection
    {
        return $this->managementNurseries;
    }

    public function addManagementNurseries(ManagementNursery $managementNurseries): self
    {
        if (!$this->managementNurseries->contains($managementNurseries)) {
            $this->managementNurseries[] = $managementNurseries;
            $managementNurseries->setUser($this);
        }

        return $this;
    }

    public function removeManagementNurseries(ManagementNursery $managementNurseries): self
    {
        if ($this->managementNurseries->removeElement($managementNurseries)) {
            // set the owning side to null (unless already changed)
            if ($managementNurseries->getUser() === $this) {
                $managementNurseries->setUser(null);
            }
        }

        return $this;
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

    public function __toString(): string
    {
        return $this->getUsername();
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}

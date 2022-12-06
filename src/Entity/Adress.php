<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @ORM\Entity(repositoryClass=AdressRepository::class)
 * @UniqueEntity(fields={"company"}, message="Il existe déjà un partenaie avec cette adresse")
 */
class Adress
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $number;

    #[ORM\Column(type: 'string', length: 255)]
    private $street;

    #[ORM\Column(type: 'string')]
    private $postalCode;

    #[ORM\Column(type: 'string', length: 50)]
    private $city;

    #[ORM\OneToMany(mappedBy: 'adress', targetEntity: User::class)]
    private $users;

    public function __construct()
    {
        $this->agencies = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function __toString()
    {
        return $this->street;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAdress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAdress() === $this) {
                $user->setAdress(null);
            }
        }

        return $this;
    }
}

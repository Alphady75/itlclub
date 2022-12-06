<?php

namespace App\Entity;

use App\Repository\AgenceAdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;

#[ORM\Entity(repositoryClass: AgenceAdressRepository::class)]
#[ORM\HasLifecycleCallbacks]
class AgenceAdress
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

    #[ORM\ManyToOne(targetEntity: Agency::class, inversedBy: 'agenceAdresses')]
    private $agence;

    #[ORM\OneToMany(mappedBy: 'agenceadresse', targetEntity: User::class)]
    private $users;

    #[ORM\OneToMany(mappedBy: 'agenceadresse', targetEntity: Company::class)]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $companies;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->companies = new ArrayCollection();
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

    public function getAgence(): ?Agency
    {
        return $this->agence;
    }

    public function setAgence(?Agency $agence): self
    {
        $this->agence = $agence;

        return $this;
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
            $user->setAgenceadresse($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgenceadresse() === $this) {
                $user->setAgenceadresse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setAgenceadresse($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getAgenceadresse() === $this) {
                $company->setAgenceadresse(null);
            }
        }

        return $this;
    }
}

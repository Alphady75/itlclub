<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;

#[ORM\Entity(repositoryClass: AgencyRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Agency
{
    use Timestamp;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: AgenceAdress::class)]
    private $agenceAdresses;

    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Solipac::class, orphanRemoval: true)]
    private Collection $solipacs;

    #[ORM\ManyToOne(inversedBy: 'agencies')]
    private ?User $commercial = null;

    public function __construct()
    {
        $this->adress = new ArrayCollection();
        $this->agenceAdresses = new ArrayCollection();
        $this->solipacs = new ArrayCollection();
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

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|AgenceAdress[]
     */
    public function getAgenceAdresses(): Collection
    {
        return $this->agenceAdresses;
    }

    public function addAgenceAdress(AgenceAdress $agenceAdress): self
    {
        if (!$this->agenceAdresses->contains($agenceAdress)) {
            $this->agenceAdresses[] = $agenceAdress;
            $agenceAdress->setAgence($this);
        }

        return $this;
    }

    public function removeAgenceAdress(AgenceAdress $agenceAdress): self
    {
        if ($this->agenceAdresses->removeElement($agenceAdress)) {
            // set the owning side to null (unless already changed)
            if ($agenceAdress->getAgence() === $this) {
                $agenceAdress->setAgence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Solipac>
     */
    public function getSolipacs(): Collection
    {
        return $this->solipacs;
    }

    public function addSolipac(Solipac $solipac): self
    {
        if (!$this->solipacs->contains($solipac)) {
            $this->solipacs->add($solipac);
            $solipac->setAgency($this);
        }

        return $this;
    }

    public function removeSolipac(Solipac $solipac): self
    {
        if ($this->solipacs->removeElement($solipac)) {
            // set the owning side to null (unless already changed)
            if ($solipac->getAgency() === $this) {
                $solipac->setAgency(null);
            }
        }

        return $this;
    }

    public function getCommercial(): ?User
    {
        return $this->commercial;
    }

    public function setCommercial(?User $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Demande
{

    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $hidenprofil;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $downloaddata;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $deletedata;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'demandes')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $user;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $statut;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $deleteCompte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHidenprofil(): ?bool
    {
        return $this->hidenprofil;
    }

    public function setHidenprofil(?bool $hidenprofil): self
    {
        $this->hidenprofil = $hidenprofil;

        return $this;
    }

    public function getDownloaddata(): ?bool
    {
        return $this->downloaddata;
    }

    public function setDownloaddata(?bool $downloaddata): self
    {
        $this->downloaddata = $downloaddata;

        return $this;
    }

    public function getDeletedata(): ?bool
    {
        return $this->deletedata;
    }

    public function setDeletedata(?bool $deletedata): self
    {
        $this->deletedata = $deletedata;

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

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDeleteCompte(): ?bool
    {
        return $this->deleteCompte;
    }

    public function setDeleteCompte(?bool $deleteCompte): self
    {
        $this->deleteCompte = $deleteCompte;

        return $this;
    }
}

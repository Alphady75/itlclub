<?php

namespace App\Entity;

use App\Entity\Traits\SubscriptionDate;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cet email")
 * @Vich\Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    use SubscriptionDate;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $agenceadresse_id;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $birthdayDate;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $job;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Company::class, cascade: ['remove'], orphanRemoval: true)]
    private $company;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Offres::class)]
    private $offres;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $telephone;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $hidenProfil;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $downloadData;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Demande::class, cascade: ['remove'], orphanRemoval: true)]
    private $demandes;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $deleteData;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $deleteCompte;

    /**
     * @Vich\UploadableField(mapping="rib_files", fileNameProperty="rib")
     * @var File
     */
    private $ribFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $rib;

    /**
     * @Vich\UploadableField(mapping="cni_files", fileNameProperty="cni")
     * @var File
     */
    private $cniFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cni;

    #[ORM\Column(type: 'string', nullable: true)]
    private $numeroCompte;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $validateNumCompte;

    #[ORM\ManyToOne(targetEntity: Adress::class, inversedBy: 'users')]
    private $adress;

    #[ORM\ManyToOne(targetEntity: AgenceAdress::class, inversedBy: 'users')]
    private $agenceadresse;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $partenaire;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $civilite = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'users')]
    private ?self $commercial = null;

    #[ORM\OneToMany(mappedBy: 'commercial', targetEntity: self::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'commercial', targetEntity: Agency::class)]
    private Collection $agencies;

    #[ORM\OneToMany(mappedBy: 'commercial', targetEntity: Contract::class)]
    private Collection $contracts;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->demandes = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->agencies = new ArrayCollection();
        $this->contracts = new ArrayCollection();
    }

    public function setRibFile(?File $rib = null)
    {
        $this->ribFile = $rib;

        if ($rib) {
            $this->setSubscriptionDate(new \DateTimeImmutable());
        }
    }

    public function getRibFile(): ?File
    {
        return $this->ribFile;
    }

    public function setCniFile(?File $cni = null)
    {
        $this->cniFile = $cni;

        if ($cni) {
            $this->setSubscriptionDate(new \DateTimeImmutable());
        }
    }

    public function getCniFile(): ?File
    {
        return $this->cniFile;
    }

    public function serialize() {

        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));

    }

    public function unserialize($serialized) {

        list (
            $this->id,
            $this->email,
            $this->password,
        ) = unserialize($serialized);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getagenceadresse_id(): ?int
    {
        return $this->agenceadresse_id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthdayDate(): ?\DateTimeInterface
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate(?\DateTimeInterface $birthdayDate): self
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): self
    {
        // set the owning side of the relation if necessary
        if ($company->getUser() !== $this) {
            $company->setUser($this);
        }

        $this->company = $company;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Offres[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offres $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setUser($this);
        }

        return $this;
    }

    public function removeOffre(Offres $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getUser() === $this) {
                $offre->setUser(null);
            }
        }

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|Demande[]
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): self
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes[] = $demande;
            $demande->setUser($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): self
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getUser() === $this) {
                $demande->setUser(null);
            }
        }

        return $this;
    }

    public function getHidenProfil(): ?bool
    {
        return $this->hidenProfil;
    }

    public function setHidenProfil(?bool $hidenProfil): self
    {
        $this->hidenProfil = $hidenProfil;

        return $this;
    }

    public function getDownloadData(): ?bool
    {
        return $this->downloadData;
    }

    public function setDownloadData(?bool $downloadData): self
    {
        $this->downloadData = $downloadData;

        return $this;
    }

    public function getDeleteData(): ?bool
    {
        return $this->deleteData;
    }

    public function setDeleteData(?bool $deleteData): self
    {
        $this->deleteData = $deleteData;

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

    public function __toString()
    {
        if ($this->getName()) {
            return $this->getName() . ' ' . $this->getLastname();
        }else {
            return $this->email;
        }
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(?string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }

    public function getCni(): ?string
    {
        return $this->cni;
    }

    public function setCni(?string $cni): self
    {
        $this->cni = $cni;

        return $this;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numeroCompte;
    }

    public function setNumeroCompte(?string $numeroCompte): self
    {
        $this->numeroCompte = $numeroCompte;

        return $this;
    }

    public function getValidateNumCompte(): ?bool
    {
        return $this->validateNumCompte;
    }

    public function setValidateNumCompte(?bool $validateNumCompte): self
    {
        $this->validateNumCompte = $validateNumCompte;

        return $this;
    }

    public function getAdress(): ?Adress
    {
        return $this->adress;
    }

    public function setAdress(?Adress $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getAgenceadresse(): ?AgenceAdress
    {
        return $this->agenceadresse;
    }


    public function setAgenceadresse(?AgenceAdress $agenceadresse): self
    {
        $this->agenceadresse = $agenceadresse;

        return $this;
    }

    public function getPartenaire(): ?bool
    {
        return $this->partenaire;
    }

    public function setPartenaire(bool $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getCommercial(): ?self
    {
        return $this->commercial;
    }

    public function setCommercial(?self $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCommercial($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCommercial() === $this) {
                $user->setCommercial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Agency>
     */
    public function getAgencies(): Collection
    {
        return $this->agencies;
    }

    public function addAgency(Agency $agency): self
    {
        if (!$this->agencies->contains($agency)) {
            $this->agencies->add($agency);
            $agency->setCommercial($this);
        }

        return $this;
    }

    public function removeAgency(Agency $agency): self
    {
        if ($this->agencies->removeElement($agency)) {
            // set the owning side to null (unless already changed)
            if ($agency->getCommercial() === $this) {
                $agency->setCommercial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contract>
     */
    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function addContract(Contract $contract): self
    {
        if (!$this->contracts->contains($contract)) {
            $this->contracts->add($contract);
            $contract->setCommercial($this);
        }

        return $this;
    }

    public function removeContract(Contract $contract): self
    {
        if ($this->contracts->removeElement($contract)) {
            // set the owning side to null (unless already changed)
            if ($contract->getCommercial() === $this) {
                $contract->setCommercial(null);
            }
        }

        return $this;
    }
}

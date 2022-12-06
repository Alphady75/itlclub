<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\Traits\Timestamp;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @UniqueEntity(fields={"user"}, message="Cet adhérent a déjà un contrat de partenariat")
 * @Vich\Uploadable
 */
class Company
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', nullable: true)]
    private $siret;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $nbEmployees;

    #[ORM\Column(type: 'string', nullable: true)]
    private $phoneNumber;
   
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    /**
     * @Vich\UploadableField(mapping="companies_img", fileNameProperty="picture")
     * @var File|null
     */

    //@Assert\Image(maxSize="3M", maxSizeMessage="Image trop volumineuse maximum 3Mb")
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $picture;

    #[ORM\OneToOne(inversedBy: 'company', targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $user;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Offres::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private $offres;

    #[ORM\OneToOne(mappedBy: 'company', targetEntity: Contract::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $contract;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private $metier;

    #[ORM\Column(type: 'text', nullable: true)]
    private $services;

    #[ORM\OneToMany(mappedBy: 'company', targetEntity: Document::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $documents;

    #[ORM\ManyToOne(targetEntity: AgenceAdress::class, inversedBy: 'companies', cascade: ['persist', 'remove'])]
    private $agenceadresse;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->documents = new ArrayCollection();
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getNbEmployees(): ?int
    {
        return $this->nbEmployees;
    }

    public function setNbEmployees(?int $nbEmployees): self
    {
        $this->nbEmployees = $nbEmployees;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

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
            $offre->setCompagny($this);
        }

        return $this;
    }

    public function removeOffre(Offres $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getCompagny() === $this) {
                $offre->setCompagny(null);
            }
        }

        return $this;
    }

    public function getContract(): ?Contract
    {
        return $this->contract;
    }

    public function setContract(Contract $contract): self
    {
        // set the owning side of the relation if necessary
        if ($contract->getCompany() !== $this) {
            $contract->setCompany($this);
        }

        $this->contract = $contract;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getMetier(): ?string
    {
        return $this->metier;
    }

    public function setMetier(?string $metier): self
    {
        $this->metier = $metier;

        return $this;
    }

    public function getServices(): ?string
    {
        return $this->services;
    }

    public function setServices(?string $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setCompany($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getCompany() === $this) {
                $document->setCompany(null);
            }
        }

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
}

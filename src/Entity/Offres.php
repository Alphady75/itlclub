<?php

namespace App\Entity;

use App\Repository\OffresRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\Traits\Timestamp;

#[ORM\Entity(repositoryClass: OffresRepository::class)]
#[ORM\HasLifecycleCallbacks]

/**
 * @Vich\Uploadable
 */
class Offres
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    /**
     * @Vich\UploadableField(mapping="offres_img", fileNameProperty="imageName")
     * @var File|null
     */

    //@Assert\Image(maxSize="3M", maxSizeMessage="Image trop volumineuse maximum 3Mb")
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageName;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'text', nullable: true)]
    private $contenu;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'offres')]
    #[ORM\JoinColumn(onDelete: "CASCADE", nullable: true)]
    private $company;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'offres')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $user;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'boolean')]
    private $visibility;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $deleted;

    #[ORM\Column(type: 'string', nullable: true)]
    private $partenaireInfo1;

    #[ORM\Column(type: 'string', nullable: true)]
    private $partenaireInfo2;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $partenaireInfoVisibility;

    #[ORM\ManyToOne(targetEntity: CategorieOffre::class, inversedBy: 'offres')]
    private $categorieoffre;

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

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getPartenaireInfo1(): ?string
    {
        return $this->partenaireInfo1;
    }

    public function setPartenaireInfo1(?string $partenaireInfo1): self
    {
        $this->partenaireInfo1 = $partenaireInfo1;

        return $this;
    }

    public function getPartenaireInfo2(): ?string
    {
        return $this->partenaireInfo2;
    }

    public function setPartenaireInfo2(?string $partenaireInfo2): self
    {
        $this->partenaireInfo2 = $partenaireInfo2;

        return $this;
    }

    public function getPartenaireInfoVisibility(): ?bool
    {
        return $this->partenaireInfoVisibility;
    }

    public function setPartenaireInfoVisibility(?bool $partenaireInfoVisibility): self
    {
        $this->partenaireInfoVisibility = $partenaireInfoVisibility;

        return $this;
    }

    public function getCategorieoffre(): ?CategorieOffre
    {
        return $this->categorieoffre;
    }

    public function setCategorieoffre(?CategorieOffre $categorieoffre): self
    {
        $this->categorieoffre = $categorieoffre;

        return $this;
    }
}

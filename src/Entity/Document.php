<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]

/**
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Document
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Vich\UploadableField(mapping="companies_documents", fileNameProperty="fileUrl")
     * @var File
     */
    private $documentFile;

    #[ORM\Column(type: 'string', length: 255)]
    private $fileUrl;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $company;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function setFileUrl(?string $fileUrl): self
    {
        $this->fileUrl = $fileUrl;

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

    public function setDocumentFile(File $document = null)
    {
        $this->documentFile = $document;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($document) {
            // if 'updatedAt' is not defined in your entity, use another property
           $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getDocumentFile()
    {
        return $this->documentFile;
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
}

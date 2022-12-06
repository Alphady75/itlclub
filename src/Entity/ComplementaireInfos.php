<?php

namespace App\Entity;

use App\Repository\ComplementaireInfosRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestamp;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ComplementaireInfosRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @Vich\Uploadable
 */
class ComplementaireInfos
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * @Vich\UploadableField(mapping="rib_files", fileNameProperty="rib")
     * @var File
     */
    private $ribFile;

    #[ORM\Column(type: 'string', length: 255)]
    private $rib;

    /**
     * @Vich\UploadableField(mapping="cni_images", fileNameProperty="cni")
     * @var File
     */
    private $cniImg;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cni;

    #[ORM\OneToOne(inversedBy: 'complementaireInfos', targetEntity: Company::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private $company;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $iban;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $bic;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $banqueName;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $fraisactivationCarte;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $conditiongeneraleVente;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $testEligibilite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setRibFile(?File $rib = null)
    {
        $this->ribFile = $rib;

        if ($rib) {
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getRibFile(): ?string
    {
        return $this->ribFile;
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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $cniImg
     */
    public function setCniImg(?File $cniImg = null): void
    {
        $this->cniImg = $cniImg;

        if (null !== $cniImg) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->bic;
    }

    public function setBic(?string $bic): self
    {
        $this->bic = $bic;

        return $this;
    }

    public function getBanqueName(): ?string
    {
        return $this->banqueName;
    }

    public function setBanqueName(?string $banqueName): self
    {
        $this->banqueName = $banqueName;

        return $this;
    }

    public function getFraisactivationCarte(): ?bool
    {
        return $this->fraisactivationCarte;
    }

    public function setFraisactivationCarte(?bool $fraisactivationCarte): self
    {
        $this->fraisactivationCarte = $fraisactivationCarte;

        return $this;
    }

    public function getConditiongeneraleVente(): ?bool
    {
        return $this->conditiongeneraleVente;
    }

    public function setConditiongeneraleVente(?bool $conditiongeneraleVente): self
    {
        $this->conditiongeneraleVente = $conditiongeneraleVente;

        return $this;
    }

    public function getTestEligibilite(): ?bool
    {
        return $this->testEligibilite;
    }

    public function setTestEligibilite(?bool $testEligibilite): self
    {
        $this->testEligibilite = $testEligibilite;

        return $this;
    }
}

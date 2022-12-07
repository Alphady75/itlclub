<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\SolipacRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SolipacRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @UniqueEntity(fields={"societeEmail"}, message="Une entreprise à déjà soumis les informations avec cet email")
 * @UniqueEntity(fields={"gerantMail"}, message="Un gerant à déjà soumis les informations avec cet email")
 */
class Solipac
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'solipacs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agency $agency = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conseiller = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $societe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $societeTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $societeEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $societeAdresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $societeSiret = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $societeDateCreation = null;

    #[ORM\Column(nullable: true)]
    private ?int $societeEffectif = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $societeQualification = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gerant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gerantTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gerantMail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ape = null;

    #[ORM\Column(nullable: true)]
    private ?int $ca2021 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $composition = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $zoneGeoInter = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pacAirEau = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pacAirEauMarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $pacAirEauVolume = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pacAirAir = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pacAirAirMarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $pacAirAirVolume = null;

    #[ORM\Column]
    private ?bool $ballonThermo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ballonThermoMarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $ballonThermoVolume = null;

    #[ORM\Column(nullable: true)]
    private ?bool $biomasse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $biomasseMarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $biomasseVolume = null;

    #[ORM\Column(nullable: true)]
    private ?bool $planChauffant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $planChauffantMarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $planChauffantVolume = null;

    #[ORM\Column(nullable: true)]
    private ?bool $adoucisseur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adoucisseurMarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $adoucisseurVolume = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ventilation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ventilationMarque = null;

    #[ORM\Column(nullable: true)]
    private ?int $ventilationVolume = null;

    #[ORM\Column(nullable: true)]
    private ?bool $renovation = null;

    #[ORM\Column(nullable: true)]
    private ?float $renovPourcentage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $neuf = null;

    #[ORM\Column(nullable: true)]
    private ?float $neufPourcentage = null;

    #[ORM\Column(nullable: true)]
    private ?float $volumeAchat = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $nbJours = [];

    #[ORM\Column(nullable: true)]
    private ?float $encoursDemande = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $demandeMethode = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $formationCom = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $formationQua = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $accompTech = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $accompAdmin = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $accompAssurance = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $besoinApprenti = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $objClient = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $appreciationGen = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRdv1 = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRdv2 = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private $origineContact = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $autreCommentatire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(?Agency $agency): self
    {
        $this->agency = $agency;

        return $this;
    }

    public function getConseiller(): ?string
    {
        return $this->conseiller;
    }

    public function setConseiller(?string $conseiller): self
    {
        $this->conseiller = $conseiller;

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    public function getSocieteTel(): ?string
    {
        return $this->societeTel;
    }

    public function setSocieteTel(?string $societeTel): self
    {
        $this->societeTel = $societeTel;

        return $this;
    }

    public function getSocieteEmail(): ?string
    {
        return $this->societeEmail;
    }

    public function setSocieteEmail(?string $societeEmail): self
    {
        $this->societeEmail = $societeEmail;

        return $this;
    }

    public function getSocieteAdresse(): ?string
    {
        return $this->societeAdresse;
    }

    public function setSocieteAdresse(?string $societeAdresse): self
    {
        $this->societeAdresse = $societeAdresse;

        return $this;
    }

    public function getSocieteSiret(): ?string
    {
        return $this->societeSiret;
    }

    public function setSocieteSiret(?string $societeSiret): self
    {
        $this->societeSiret = $societeSiret;

        return $this;
    }

    public function getSocieteDateCreation(): ?\DateTimeInterface
    {
        return $this->societeDateCreation;
    }

    public function setSocieteDateCreation(?\DateTimeInterface $societeDateCreation): self
    {
        $this->societeDateCreation = $societeDateCreation;

        return $this;
    }

    public function getSocieteEffectif(): ?int
    {
        return $this->societeEffectif;
    }

    public function setSocieteEffectif(?int $societeEffectif): self
    {
        $this->societeEffectif = $societeEffectif;

        return $this;
    }

    public function getSocieteQualification(): ?array
    {
        return $this->societeQualification;
    }

    public function setSocieteQualification(?array $societeQualification): self
    {
        $this->societeQualification = $societeQualification;

        return $this;
    }

    public function getGerant(): ?string
    {
        return $this->gerant;
    }

    public function setGerant(?string $gerant): self
    {
        $this->gerant = $gerant;

        return $this;
    }

    public function getGerantTel(): ?string
    {
        return $this->gerantTel;
    }

    public function setGerantTel(?string $gerantTel): self
    {
        $this->gerantTel = $gerantTel;

        return $this;
    }

    public function getGerantMail(): ?string
    {
        return $this->gerantMail;
    }

    public function setGerantMail(?string $gerantMail): self
    {
        $this->gerantMail = $gerantMail;

        return $this;
    }

    public function getApe(): ?string
    {
        return $this->ape;
    }

    public function setApe(?string $ape): self
    {
        $this->ape = $ape;

        return $this;
    }

    public function getCa2021(): ?int
    {
        return $this->ca2021;
    }

    public function setCa2021(?int $ca2021): self
    {
        $this->ca2021 = $ca2021;

        return $this;
    }

    public function getComposition(): ?string
    {
        return $this->composition;
    }

    public function setComposition(?string $composition): self
    {
        $this->composition = $composition;

        return $this;
    }

    public function getZoneGeoInter(): ?string
    {
        return $this->zoneGeoInter;
    }

    public function setZoneGeoInter(?string $zoneGeoInter): self
    {
        $this->zoneGeoInter = $zoneGeoInter;

        return $this;
    }

    public function isPacAirEau(): ?bool
    {
        return $this->pacAirEau;
    }

    public function setPacAirEau(?bool $pacAirEau): self
    {
        $this->pacAirEau = $pacAirEau;

        return $this;
    }

    public function getPacAirEauMarque(): ?string
    {
        return $this->pacAirEauMarque;
    }

    public function setPacAirEauMarque(?string $pacAirEauMarque): self
    {
        $this->pacAirEauMarque = $pacAirEauMarque;

        return $this;
    }

    public function getPacAirEauVolume(): ?int
    {
        return $this->pacAirEauVolume;
    }

    public function setPacAirEauVolume(?int $pacAirEauVolume): self
    {
        $this->pacAirEauVolume = $pacAirEauVolume;

        return $this;
    }

    public function isPacAirAir(): ?bool
    {
        return $this->pacAirAir;
    }

    public function setPacAirAir(?bool $pacAirAir): self
    {
        $this->pacAirAir = $pacAirAir;

        return $this;
    }

    public function getPacAirAirMarque(): ?string
    {
        return $this->pacAirAirMarque;
    }

    public function setPacAirAirMarque(?string $pacAirAirMarque): self
    {
        $this->pacAirAirMarque = $pacAirAirMarque;

        return $this;
    }

    public function getPacAirAirVolume(): ?int
    {
        return $this->pacAirAirVolume;
    }

    public function setPacAirAirVolume(?int $pacAirAirVolume): self
    {
        $this->pacAirAirVolume = $pacAirAirVolume;

        return $this;
    }

    public function isBallonThermo(): ?bool
    {
        return $this->ballonThermo;
    }

    public function setBallonThermo(bool $ballonThermo): self
    {
        $this->ballonThermo = $ballonThermo;

        return $this;
    }

    public function getBallonThermoMarque(): ?string
    {
        return $this->ballonThermoMarque;
    }

    public function setBallonThermoMarque(?string $ballonThermoMarque): self
    {
        $this->ballonThermoMarque = $ballonThermoMarque;

        return $this;
    }

    public function getBallonThermoVolume(): ?int
    {
        return $this->ballonThermoVolume;
    }

    public function setBallonThermoVolume(?int $ballonThermoVolume): self
    {
        $this->ballonThermoVolume = $ballonThermoVolume;

        return $this;
    }

    public function isBiomasse(): ?bool
    {
        return $this->biomasse;
    }

    public function setBiomasse(?bool $biomasse): self
    {
        $this->biomasse = $biomasse;

        return $this;
    }

    public function getBiomasseMarque(): ?string
    {
        return $this->biomasseMarque;
    }

    public function setBiomasseMarque(?string $biomasseMarque): self
    {
        $this->biomasseMarque = $biomasseMarque;

        return $this;
    }

    public function getBiomasseVolume(): ?int
    {
        return $this->biomasseVolume;
    }

    public function setBiomasseVolume(?int $biomasseVolume): self
    {
        $this->biomasseVolume = $biomasseVolume;

        return $this;
    }

    public function isPlanChauffant(): ?bool
    {
        return $this->planChauffant;
    }

    public function setPlanChauffant(?bool $planChauffant): self
    {
        $this->planChauffant = $planChauffant;

        return $this;
    }

    public function getPlanChauffantMarque(): ?string
    {
        return $this->planChauffantMarque;
    }

    public function setPlanChauffantMarque(?string $planChauffantMarque): self
    {
        $this->planChauffantMarque = $planChauffantMarque;

        return $this;
    }

    public function getPlanChauffantVolume(): ?int
    {
        return $this->planChauffantVolume;
    }

    public function setPlanChauffantVolume(?int $planChauffantVolume): self
    {
        $this->planChauffantVolume = $planChauffantVolume;

        return $this;
    }

    public function isAdoucisseur(): ?bool
    {
        return $this->adoucisseur;
    }

    public function setAdoucisseur(?bool $adoucisseur): self
    {
        $this->adoucisseur = $adoucisseur;

        return $this;
    }

    public function getAdoucisseurMarque(): ?string
    {
        return $this->adoucisseurMarque;
    }

    public function setAdoucisseurMarque(?string $adoucisseurMarque): self
    {
        $this->adoucisseurMarque = $adoucisseurMarque;

        return $this;
    }

    public function getAdoucisseurVolume(): ?int
    {
        return $this->adoucisseurVolume;
    }

    public function setAdoucisseurVolume(?int $adoucisseurVolume): self
    {
        $this->adoucisseurVolume = $adoucisseurVolume;

        return $this;
    }

    public function isVentilation(): ?bool
    {
        return $this->ventilation;
    }

    public function setVentilation(?bool $ventilation): self
    {
        $this->ventilation = $ventilation;

        return $this;
    }

    public function getVentilationMarque(): ?string
    {
        return $this->ventilationMarque;
    }

    public function setVentilationMarque(?string $ventilationMarque): self
    {
        $this->ventilationMarque = $ventilationMarque;

        return $this;
    }

    public function getVentilationVolume(): ?int
    {
        return $this->ventilationVolume;
    }

    public function setVentilationVolume(?int $ventilationVolume): self
    {
        $this->ventilationVolume = $ventilationVolume;

        return $this;
    }

    public function isRenovation(): ?bool
    {
        return $this->renovation;
    }

    public function setRenovation(?bool $renovation): self
    {
        $this->renovation = $renovation;

        return $this;
    }

    public function getRenovPourcentage(): ?float
    {
        return $this->renovPourcentage;
    }

    public function setRenovPourcentage(?float $renovPourcentage): self
    {
        $this->renovPourcentage = $renovPourcentage;

        return $this;
    }

    public function isNeuf(): ?bool
    {
        return $this->neuf;
    }

    public function setNeuf(?bool $neuf): self
    {
        $this->neuf = $neuf;

        return $this;
    }

    public function getNeufPourcentage(): ?float
    {
        return $this->neufPourcentage;
    }

    public function setNeufPourcentage(?float $neufPourcentage): self
    {
        $this->neufPourcentage = $neufPourcentage;

        return $this;
    }

    public function getVolumeAchat(): ?float
    {
        return $this->volumeAchat;
    }

    public function setVolumeAchat(?float $volumeAchat): self
    {
        $this->volumeAchat = $volumeAchat;

        return $this;
    }

    public function getNbJours(): ?array
    {
        return $this->nbJours;
    }

    public function setNbJours(?array $nbJours): self
    {
        $this->nbJours = $nbJours;

        return $this;
    }

    public function getEncoursDemande(): ?float
    {
        return $this->encoursDemande;
    }

    public function setEncoursDemande(?float $encoursDemande): self
    {
        $this->encoursDemande = $encoursDemande;

        return $this;
    }

    public function getDemandeMethode(): ?array
    {
        return $this->demandeMethode;
    }

    public function setDemandeMethode(?array $demandeMethode): self
    {
        $this->demandeMethode = $demandeMethode;

        return $this;
    }

    public function getFormationCom(): ?array
    {
        return $this->formationCom;
    }

    public function setFormationCom(?array $formationCom): self
    {
        $this->formationCom = $formationCom;

        return $this;
    }

    public function getFormationQua(): ?array
    {
        return $this->formationQua;
    }

    public function setFormationQua(?array $formationQua): self
    {
        $this->formationQua = $formationQua;

        return $this;
    }

    public function getAccompTech(): ?array
    {
        return $this->accompTech;
    }

    public function setAccompTech(?array $accompTech): self
    {
        $this->accompTech = $accompTech;

        return $this;
    }

    public function getAccompAdmin(): ?array
    {
        return $this->accompAdmin;
    }

    public function setAccompAdmin(?array $accompAdmin): self
    {
        $this->accompAdmin = $accompAdmin;

        return $this;
    }

    public function getAccompAssurance(): ?array
    {
        return $this->accompAssurance;
    }

    public function setAccompAssurance(?array $accompAssurance): self
    {
        $this->accompAssurance = $accompAssurance;

        return $this;
    }

    public function getBesoinApprenti(): ?array
    {
        return $this->besoinApprenti;
    }

    public function setBesoinApprenti(?array $besoinApprenti): self
    {
        $this->besoinApprenti = $besoinApprenti;

        return $this;
    }

    public function getObjClient(): ?string
    {
        return $this->objClient;
    }

    public function setObjClient(?string $objClient): self
    {
        $this->objClient = $objClient;

        return $this;
    }

    public function getAppreciationGen(): ?string
    {
        return $this->appreciationGen;
    }

    public function setAppreciationGen(?string $appreciationGen): self
    {
        $this->appreciationGen = $appreciationGen;

        return $this;
    }

    public function getDateRdv1(): ?\DateTimeInterface
    {
        return $this->dateRdv1;
    }

    public function setDateRdv1(?\DateTimeInterface $dateRdv1): self
    {
        $this->dateRdv1 = $dateRdv1;

        return $this;
    }

    public function getDateRdv2(): ?\DateTimeInterface
    {
        return $this->dateRdv2;
    }

    public function setDateRdv2(?\DateTimeInterface $dateRdv2): self
    {
        $this->dateRdv2 = $dateRdv2;

        return $this;
    }

    public function getOrigineContact(): ?array
    {
        return $this->origineContact;
    }

    public function setOrigineContact(?array $origineContact): self
    {
        $this->origineContact = $origineContact;

        return $this;
    }

    public function getAutreCommentatire(): ?string
    {
        return $this->autreCommentatire;
    }

    public function setAutreCommentatire(?string $autreCommentatire): self
    {
        $this->autreCommentatire = $autreCommentatire;

        return $this;
    }
}

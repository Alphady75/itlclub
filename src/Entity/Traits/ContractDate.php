<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;

trait ContractDate {

    #[ORM\Column(type: 'datetime')]
    private $contractDate;

    public function getContractDate(): ?\DateTimeInterface
    {
        return $this->contractDate;
    }

    public function setContractDate(\DateTimeInterface $contractDate): self
    {
        $this->contractDate = $contractDate;

        return $this;
    }

    /**
     * Permet d'automatiser la date et de création et de modification
     */
    #[ORM\PrePersist]
    public function updateTimestamps(): void
    {
        if($this->getContractDate() === null){
            $this->setContractDate(new \DateTimeImmutable());
        }
    }

}

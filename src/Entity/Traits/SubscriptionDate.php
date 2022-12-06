<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;

trait SubscriptionDate {

    #[ORM\Column(type: 'datetime')]
    private $subscriptionDate;

    public function getSubscriptionDate(): ?\DateTimeInterface
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(\DateTimeInterface $subscriptionDate): self
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    /**
     * Permet d'automatiser la date et de création et de modification
     */
    #[ORM\PrePersist]
    public function updateTimestamps(): void
    {
        if($this->getSubscriptionDate() === null){
            $this->setSubscriptionDate(new \DateTimeImmutable());
        }
    }

}

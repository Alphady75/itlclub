<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class SearchOffres {

    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $q = null;
}
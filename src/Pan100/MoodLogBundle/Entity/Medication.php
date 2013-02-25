<?php

namespace Pan100\MoodLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="medication")
 */
class Medication
{
    /**
     * @ORM\Id
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $amountMg;          

    public function __construct()
    {
        parent::__construct();
    }
}

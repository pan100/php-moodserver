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
     * @ORM\Column(length=32)
     */
    private $name;

    /**
     * @ORM\Id    
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $amount_mg;
    /**
     * @ORM\ManyToMany(targetEntity="Day", mappedBy="medications")
     **/
    private $days;          

    public function __construct()
    {
    }
}

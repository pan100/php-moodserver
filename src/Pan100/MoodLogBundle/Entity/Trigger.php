<?php

namespace Pan100\MoodLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="trigger")
 */
class Trigger
{
    /**
     * @ORM\Id
     * @ORM\Column(length="128")
     */
    private $triggertext;
    //TODO fiks DocBlock med mange til mange
    private $days;       

    public function __construct()
    {
        parent::__construct();
    }
}

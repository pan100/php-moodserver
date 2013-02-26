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
     * @ORM\Column(length=128)
     */
    private $triggertext;
    /**@ORM\Id
     * @ORM\ManyToMany(targetEntity="Day", mappedBy="triggers")
     **/
    private $days;       

    public function __construct()
    {
        $this->days = new \Doctrine\Common\Collections\ArrayCollection();
    }
}

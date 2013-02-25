<?php

namespace Pan100\MoodLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="day")
 */
class Day
{
    /**
     * @ORM\Id
     * @ORM\Column(type="date")
     */
    private $date;
    
    //TODO Spesifiser docblock foreign key og id
    private $user_id;

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $moodLow; 

    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $moodHigh;
    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $sleepHours;
    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */    
    private $anxiety;
    /**
     * @ORM\Column(type="integer", nullable=TRUE)
     */
    private $irritability;

    /**
     * @ORM\Column(type="text", nullable=TRUE)
     */
    private $diaryText;
    /**
     * @ORM\ManyToMany(targetEntity="trigger", mappedBy="hasAccessToMe")
     **/
    private $triggers = null;
    //TODO DocBlock manyToMany
    private $medications = null;        

    public function __construct()
    {
        parent::__construct();
    }
}

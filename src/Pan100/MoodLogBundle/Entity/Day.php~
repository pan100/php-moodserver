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
     * @ORM\ManyToMany(targetEntity="Trigger", inversedBy="days")
     * @ORM\JoinTable(name="triggers_days",
     *      joinColumns={@ORM\JoinColumn(name="day_date", referencedColumnName="date")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="triggertext", referencedColumnName="triggertext")}
        )
     **/
    private $triggers;
    /**
     * @ORM\ManyToMany(targetEntity="Medication", inversedBy="days")
     * @ORM\JoinTable(name="medications_date",
     *      joinColumns={@ORM\JoinColumn(name="day_date", referencedColumnName="date")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="med_name", referencedColumnName="name"), @ORM\JoinColumn(name="amount_mg", referencedColumnName="amount_mg")}
        )
     **/
    private $medications = null;        

    public function __construct()
    {
        $this->triggers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medications = new \Doctrine\Common\Collections\ArrayCollection();
    }
}

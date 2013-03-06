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
          * @ORM\JoinTable(name="triggers_days",
     *      joinColumns={@ORM\JoinColumn(name="triggertext", referencedColumnName="triggertext")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="day_id", referencedColumnName="id")}
        )
     **/
    private $days;       

    public function __construct()
    {
        $this->days = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set triggertext
     *
     * @param string $triggertext
     * @return Trigger
     */
    public function setTriggertext($triggertext)
    {
        $this->triggertext = $triggertext;
    
        return $this;
    }

    /**
     * Get triggertext
     *
     * @return string 
     */
    public function getTriggertext()
    {
        return $this->triggertext;
    }

        /**
     * Add Days
     *
     * @param \Pan100\MoodLogBundle\Entity\Day $id
     * @return Trigger
     */
    public function addDay(\Pan100\MoodLogBundle\Entity\Day $days)
    {
        $this->days[] = $days;
    
        return $this;
    }

    /**
     * Remove days
     *
     * @param \Pan100\MoodLogBundle\Entity\Day $days
     */
    public function removeDay(\Pan100\MoodLogBundle\Entity\Day $days)
    {
        $this->days->removeElement($days);
    }

    /**
     * Get days
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDays()
    {
        return $this->days;
    }
}
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
        $this->days = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Medication
     */
    public function setName($name)
    {
        $name = strtolower($name);
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set amount_mg
     *
     * @param integer $amountMg
     * @return Medication
     */
    public function setAmountMg($amountMg)
    {
        $this->amount_mg = $amountMg;
    
        return $this;
    }

    /**
     * Get amount_mg
     *
     * @return integer 
     */
    public function getAmountMg()
    {
        return $this->amount_mg;
    }

    /**
     * Add days
     *
     * @param \Pan100\MoodLogBundle\Entity\Day $days
     * @return Medication
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
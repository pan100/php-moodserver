<?php

namespace Pan100\MoodLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\ExecutionContext;

/**
 * @ORM\Entity
 * @ORM\Table(name="day")
 */
class Day
{

    //uses a surrogate key due to bug in doctrine
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="days")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     **/
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
     *      joinColumns={@ORM\JoinColumn(name="day_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="triggertext", referencedColumnName="triggertext")})
     **/
    private $triggers = null;
    /**
     * @ORM\ManyToMany(targetEntity="Medication", inversedBy="days")
     * @ORM\JoinTable(name="medications_day_id",
     *      joinColumns={@ORM\JoinColumn(name="day_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="med_name", referencedColumnName="name"), @ORM\JoinColumn(name="amount_mg", referencedColumnName="amount_mg")})
     **/
    private $medications = null;        

    public function __construct()
    {
        $this->triggers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->medications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Day
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set moodLow
     *
     * @param integer $moodLow
     * @return Day
     */
    public function setMoodLow($moodLow)
    {
        $this->moodLow = $moodLow;
    
        return $this;
    }

    /**
     * Get moodLow
     *
     * @return integer 
     */
    public function getMoodLow()
    {
        return $this->moodLow;
    }

    /**
     * Set moodHigh
     *
     * @param integer $moodHigh
     * @return Day
     */
    public function setMoodHigh($moodHigh)
    {
        $this->moodHigh = $moodHigh;
    
        return $this;
    }

    /**
     * Get moodHigh
     *
     * @return integer 
     */
    public function getMoodHigh()
    {
        return $this->moodHigh;
    }

    /**
     * Set sleepHours
     *
     * @param integer $sleepHours
     * @return Day
     */
    public function setSleepHours($sleepHours)
    {
        $this->sleepHours = $sleepHours;
    
        return $this;
    }

    /**
     * Get sleepHours
     *
     * @return integer 
     */
    public function getSleepHours()
    {
        return $this->sleepHours;
    }

    /**
     * Set anxiety
     *
     * @param integer $anxiety
     * @return Day
     */
    public function setAnxiety($anxiety)
    {
        $this->anxiety = $anxiety;
    
        return $this;
    }

    /**
     * Get anxiety
     *
     * @return integer 
     */
    public function getAnxiety()
    {
        return $this->anxiety;
    }

    /**
     * Set irritability
     *
     * @param integer $irritability
     * @return Day
     */
    public function setIrritability($irritability)
    {
        $this->irritability = $irritability;
    
        return $this;
    }

    /**
     * Get irritability
     *
     * @return integer 
     */
    public function getIrritability()
    {
        return $this->irritability;
    }

    /**
     * Set diaryText
     *
     * @param string $diaryText
     * @return Day
     */
    public function setDiaryText($diaryText)
    {
        $this->diaryText = $diaryText;
    
        return $this;
    }

    /**
     * Get diaryText
     *
     * @return string 
     */
    public function getDiaryText()
    {
        return $this->diaryText;
    }

    /**
     * Add triggers
     *
     * @param \Pan100\MoodLogBundle\Entity\Trigger $triggers
     * @return Day
     */
    public function addTrigger(\Pan100\MoodLogBundle\Entity\Trigger $triggers)
    {
        //if it already has the trigger, do not add it
        if(!$this->getTriggers()->contains($triggers)) {
            $this->triggers[] = $triggers;
        }
    
        return $this;
    }

    /**
     * Remove triggers
     *
     * @param \Pan100\MoodLogBundle\Entity\Trigger $triggers
     */
    public function removeTrigger(\Pan100\MoodLogBundle\Entity\Trigger $triggers)
    {
        $this->triggers->removeElement($triggers);
    }

    /**
     * Get triggers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTriggers()
    {
        return $this->triggers;
    }

    /**
     * Add medications
     *
     * @param \Pan100\MoodLogBundle\Entity\Medication $medications
     * @return Day
     */
    public function addMedication(\Pan100\MoodLogBundle\Entity\Medication $medications)
    {   
        if(!$this->getMedications()->contains($medications)) {
            $this->medications[] = $medications;
        }
        return $this;
    }

    /**
     * Remove medications
     *
     * @param \Pan100\MoodLogBundle\Entity\Medication $medications
     */
    public function removeMedication(\Pan100\MoodLogBundle\Entity\Medication $medications)
    {
        $this->medications->removeElement($medications);
    }

    /**
     * Get medications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMedications()
    {
        return $this->medications;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user_id
     *
     * @param \Pan100\MoodLogBundle\Entity\User $userId
     * @return Day
     */
    public function setUserId(\Pan100\MoodLogBundle\Entity\User $userId = null)
    {
        $this->user_id = $userId;
    
        return $this;
    }

    /**
     * Get user_id
     *
     * @return \Pan100\MoodLogBundle\Entity\User 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    static function daySorter($dayEntity1, $dayEntity2) {
        $interval = $dayEntity1->getDate()->diff($dayEntity2->getDate());
        //if the dayEntity1 is newer than dayEntity2, it is greater and we return 1. For now dont care to return 0 if identical.
        if($interval->invert == 1) {
            return +1;
        }
        else if ($interval->invert == 0) {
            return 0;
        }
        else return -1;
    }

    public function isHighLowCorrect(ExecutionContext $context) {
        if($this->moodHigh < $this->moodLow) {
            $context->addViolationAtSubPath('moodlow', "Laveste humør må være lavere enn høyeste", array(), null);
        }
    }
}
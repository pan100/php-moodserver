<?php

namespace Pan100\MoodLogBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="hasAccessToMe")
     **/
    protected $hasAccessTo = null;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="hasAccessTo")
     * @ORM\JoinTable(name="access",
     *      joinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="accessor_id", referencedColumnName="id")}
     *      )
     **/
    protected $hasAccessToMe = null; 

    /**
     * @ORM\OneToMany(targetEntity="Day", mappedBy="user_id")
     * @ORM\OrderBy({"date" = "DESC"})     
     **/    
    protected $days;

    public function __construct()
    {
        parent::__construct();
        $this->hasAccessTo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->hasAccessToMe = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add hasAccessTo
     *
     * @param \Pan100\MoodLogBundle\Entity\User $hasAccessTo
     * @return User
     */
    public function addHasAccessTo(\Pan100\MoodLogBundle\Entity\User $hasAccessTo)
    {
        $this->hasAccessTo[] = $hasAccessTo;
    
        return $this;
    }

    /**
     * Remove hasAccessTo
     *
     * @param \Pan100\MoodLogBundle\Entity\User $hasAccessTo
     */
    public function removeHasAccessTo(\Pan100\MoodLogBundle\Entity\User $hasAccessTo)
    {
        $this->hasAccessTo->removeElement($hasAccessTo);
    }

    /**
     * Get hasAccessTo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHasAccessTo()
    {
        return $this->hasAccessTo;
    }

    /**
     * Add hasAccessToMe
     *
     * @param \Pan100\MoodLogBundle\Entity\User $hasAccessToMe
     * @return User
     */
    public function addHasAccessToMe(\Pan100\MoodLogBundle\Entity\User $hasAccessToMe)
    {
        $this->hasAccessToMe[] = $hasAccessToMe;
    
        return $this;
    }

    /**
     * Remove hasAccessToMe
     *
     * @param \Pan100\MoodLogBundle\Entity\User $hasAccessToMe
     */
    public function removeHasAccessToMe(\Pan100\MoodLogBundle\Entity\User $hasAccessToMe)
    {
        $this->hasAccessToMe->removeElement($hasAccessToMe);
    }

    /**
     * Get hasAccessToMe
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHasAccessToMe()
    {
        return $this->hasAccessToMe;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set emailCanonical
     *
     * @param string $emailCanonical
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
    
        return $this;
    }

    /**
     * Get emailCanonical
     *
     * @return string 
     */
    public function getEmailCanonical()
    {
        return $this->emailCanonical;
    }

    /**
     * Add days
     *
     * @param \Pan100\MoodLogBundle\Entity\Day $days
     * @return User
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
     *
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * get days with null values for gaps
     * @return \Doctrine\Common\Collections\Collection 
     */

    public function getDaysWithNulls()
    {
        $days = $this->getDays()->toArray();
        if(empty($days)) {
            return $days;
        }
        //get the first day and find out how many days have passed
        usort($days, array("\Pan100\MoodLogBundle\Entity\Day", "daySorter"));
        $firstEntry = $days[0];
        $interval = $firstEntry->getDate()->diff(new \DateTime());
        if($interval->d == 0) {
            return new \Doctrine\Common\Collections\ArrayCollection($days);
        }
        $numberOfDaysBack = $interval->d+1;
        //create an array consisting of the number of days back
        $daysToShow = array();
        for ($i=0; $i < $numberOfDaysBack ; $i++) { 
            $date = new \DateTime();
            $date->sub(new \DateInterval('P' . $i . 'D'));
            $daysToShow[] = $date;
        }
        $daysToReturn = array();
        foreach ($daysToShow as $day) {
            //figure out if this day has an entity, if not set an empty Day object
            $dayEntityToProcess = new \Pan100\MoodLogBundle\Entity\Day();
            $dayEntityToProcess->setDate($day);
            foreach ($days as $dayEntity) {
                //check if there is a day entity
                if($day->format('Y-m-d') == $dayEntity->getDate()->format('Y-m-d')) {
                    $dayEntityToProcess = $dayEntity;
                } 
            }
            $daysToReturn[] = $dayEntityToProcess;
        }
        //return a collection
        return new \Doctrine\Common\Collections\ArrayCollection($daysToReturn);
    }
}
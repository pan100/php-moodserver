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
    protected $hasAccessTo;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="hasAccessTo")
     * @ORM\JoinTable(name="access",
     *      joinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="accessor_id", referencedColumnName="id")}
     *      )
     **/
    protected $hasAccessToMe; 

    // ...
    /**
     * @ORM\OneToMany(targetEntity="Day", mappedBy="user_id")
     **/    

    protected $days;

    public function __construct()
    {
        parent::__construct();
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
     * Get days - give integer $numberOfDays if you want to specify the number of days.
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDays()
    {
        return $this->days;
    }
}
<?php

namespace Pan100\MoodLogBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
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
    private $hasAccessToMe;    

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
     * @ORM\ManyToMany(targetEntity="Pan100\MoodLogBundle\Entity\Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;   

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
}
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
            $this->hasAccessTo = new \Doctrine\Common\Collections\ArrayCollection();
            $this->hasAccessToMe = new \Doctrine\Common\Collections\ArrayCollection();
    }
}


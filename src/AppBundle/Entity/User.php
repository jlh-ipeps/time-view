<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
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

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
    
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\LastSession")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;


    /**
     * Set session
     *
     * @param \AppBundle\Entity\LastSession $session
     *
     * @return User
     */
    public function setSession(\AppBundle\Entity\LastSession $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Get session
     *
     * @return \AppBundle\Entity\LastSession
     */
    public function getSession()
    {
        return $this->session;
    }
}

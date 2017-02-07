<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser implements AdvancedUserInterface
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
        $this->talks = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->users = new ArrayCollection();
    }
    
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\LastSession", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Talk", mappedBy="user")
     */
    private $talks;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Book", mappedBy="user")
     */
    private $books;

    /**
     * @ORM\OneToMany(targetEntity="File", mappedBy="user")
     */
    protected $users;

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

    /**
     * Add talk
     *
     * @param \AppBundle\Entity\Talk $talk
     *
     * @return User
     */
    public function addTalk(\AppBundle\Entity\Talk $talk)
    {
        $this->talks[] = $talk;

        return $this;
    }

    /**
     * Remove talk
     *
     * @param \AppBundle\Entity\Talk $talk
     */
    public function removeTalk(\AppBundle\Entity\Talk $talk)
    {
        $this->talks->removeElement($talk);
    }

    /**
     * Get talks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTalks()
    {
        return $this->talks;
    }

    /**
     * Add book
     *
     * @param \AppBundle\Entity\Book $book
     *
     * @return User
     */
    public function addBook(\AppBundle\Entity\Book $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \AppBundle\Entity\Book $book
     */
    public function removeBook(\AppBundle\Entity\Book $book)
    {
        $this->books->removeElement($book);
    }

    /**
     * Get books
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\Picture $user
     *
     * @return User
     */
    public function addUser(\AppBundle\Entity\Picture $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\Picture $user
     */
    public function removeUser(\AppBundle\Entity\Picture $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
    
//    /**
//     * Get lock status
//     *
//     * @return boolean 
//     */
//    public function getLocked()
//    {
//        return $this->locked;
//    }
    
}

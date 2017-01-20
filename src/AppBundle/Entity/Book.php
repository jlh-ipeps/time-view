<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Picture", mappedBy="book")
     */
    private $pictures;
  
    /**
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="text", nullable=true)
     */
    private $info;
    
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Tag", inversedBy="books", cascade={"persist"})
     * @ORM\JoinTable(name="book_tag",
     *      joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->tags = new ArrayCollection();
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
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Book
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Add book
     *
     * @param \AppBundle\Entity\Picture $book
     *
     * @return Book
     */
    public function addBook(\AppBundle\Entity\Picture $book)
    {
        $this->books[] = $book;

        return $this;
    }

    /**
     * Remove book
     *
     * @param \AppBundle\Entity\Picture $book
     */
    public function removeBook(\AppBundle\Entity\Picture $book)
    {
        $this->books->removeElement($book);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures()
    {
        return $this->pictures->toArray();
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Book
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    

    /**
     * Add picture
     *
     * @param \AppBundle\Entity\Picture $picture
     *
     * @return Book
     */
    public function addPicture(\AppBundle\Entity\Picture $picture)
    {
        $this->pictures[] = $picture;

        return $this;
    }

    /**
     * Remove picture
     *
     * @param \AppBundle\Entity\Picture $picture
     */
    public function removePicture(\AppBundle\Entity\Picture $picture)
    {
        $this->pictures->removeElement($picture);
    }

    
    // Notez le singulier, on ajoute une seule catégorie à la fois
    public function addtag(Tag $tag) {
        $this->tags[] = $tag;
    }

    public function removeTag(Tag $tag) {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags() {
        return $this->tags;
    }

}

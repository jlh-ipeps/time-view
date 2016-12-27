<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LastSessionRepository")
 */
class LastSession
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Languages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $locale;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Homes", cascade={"all"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $home;

    /**
     * @var int $book
     *
     * @ORM\Column(name="book", type="integer", unique=false, nullable=true)
     */
    private $book;

    /**
     * @var string $uri
     *
     * @ORM\Column(name="uri", type="string", length=255, unique=false, nullable=true)
     */
    private $uri;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set book
     *
     * @param integer $book
     *
     * @return Session
     */
    public function setBook($book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return int
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set locale
     *
     * @param \AppBundle\Entity\Languages $locale
     *
     * @return Session
     */
    public function setLocale(\AppBundle\Entity\Languages $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return \AppBundle\Entity\Languages
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set home
     *
     * @param \AppBundle\Entity\Homes $home
     *
     * @return Session
     */
    public function setHome(\AppBundle\Entity\Homes $home = null)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get home
     *
     * @return \AppBundle\Entity\Homes
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Set uri
     *
     * @param string $uri
     *
     * @return LastSession
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}

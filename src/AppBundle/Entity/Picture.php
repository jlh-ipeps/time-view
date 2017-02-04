<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PictureRepository")
 */
class Picture {

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book", inversedBy="pictures")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false) 
     */
    private $book;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\File", inversedBy="pictures")
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", nullable=false) 
     */
    private $file;
    
    /**
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    
    /**
     * @ORM\Column(name="info", type="text", nullable=true)
     */
    private $info;
    

    /**
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    private $lat;
    
    /**
     * @ORM\Column(name="lng", type="float", nullable=true)
     */
    private $lng;
    
    /**
     * @ORM\Column(name="route", type="string", length=255, nullable=true)
     */
    private $route;
    
    /**
     * @ORM\Column(name="postal_code", type="string", length=25, nullable=true)
     */
    private $postalCode;
    
    /**
     * @ORM\Column(name="locality", type="string", length=255, nullable=true)
     */
    private $locality;
    
    /**
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private $country;
    
    
    private $pictures;

   
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pictures[] = new ArrayCollection();
    }

    /**
     * Set book
     *
     * @param \AppBundle\Entity\Book $book
     *
     * @return Picture
     */
    public function setBook(\AppBundle\Entity\Book $book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \AppBundle\Entity\Book
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set file
     *
     * @param \AppBundle\Entity\File $file
     *
     * @return Picture
     */
    public function setFile(\AppBundle\Entity\File $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \AppBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Picture
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
    public function getTitle() {
        $title = $this->title;
        if ($title == NULL) {
            $title = $this->getFile()->getAlt();
        }
        return $title;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Picture
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
     * Set lat
     *
     * @param float $lat
     *
     * @return Picture
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param float $lng
     *
     * @return Picture
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Picture
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set locality
     *
     * @param string $locality
     *
     * @return Picture
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;

        return $this;
    }

    /**
     * Get locality
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Picture
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return Picture
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Picture
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }
    
    /**
     * Get pictures
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPictures() {
        return $this->file->pictures->toArray();
    }
    
}

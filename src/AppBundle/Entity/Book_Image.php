<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book_Image
 *
 * @ORM\Table(name="book_image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Book_ImageRepository")
 */
class Book_Image {

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Book", inversedBy="Book_Image")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id", nullable=false) 
     */
    private $book;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", inversedBy="Book_Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=false) 
     */
    private $image;


    /**
     * Set book
     *
     * @param \AppBundle\Entity\Book $book
     *
     * @return Book_Image
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
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Book_Image
     */
    public function setImage(\AppBundle\Entity\Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }
}

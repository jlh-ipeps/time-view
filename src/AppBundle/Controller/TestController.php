<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Image;
use AppBundle\Entity\Book_Image;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller {

  public function viewAction($id, Request $request) {

    $session = $this->get('session');
    $em = $this->getDoctrine()->getManager();
    
    $bookRepo = $em->getRepository('AppBundle:Book');
    $book = $bookRepo->find(1);
    $images = $bookRepo->find(1)->getImages();

    $bookImageRepo = $em->getRepository('AppBundle:Book_Image');
    $bookImage = $bookImageRepo->findAll()[0]->getBook();

    $imageRepo = $em->getRepository('AppBundle:Image');
    $bookOwner = $imageRepo->findAll()[0];
    $books = $imageRepo->findAll()[0]->getBooks();
            
    dump($bookOwner, $book, $bookImage, $images);die();
    return $this->render('AppBundle::test.html.twig', array(
    ));
  }
}

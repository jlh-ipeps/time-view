<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Image;
use AppBundle\Entity\Picture;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller {

  public function viewAction($id, Request $request) {

    $session = $this->get('session');
    $em = $this->getDoctrine()->getManager();
    
    $bookRepo = $em->getRepository('AppBundle:Book');
    $book = $bookRepo->find(1);
    $files = $bookRepo->find(1)->getFiles();

    $pictureRepo = $em->getRepository('AppBundle:Picture');
    $bookImage = $pictureRepo->findAll()[0]->getBook();

    $fileRepo = $em->getRepository('AppBundle:File');
    $bookOwner = $fileRepo->findAll()[0];
    $books = $fileRepo->findAll()[0]->getBooks();
            
    dump($bookOwner, $book, $bookImage, $files);die();
    return $this->render('AppBundle::test.html.twig', array(
    ));
  }
}

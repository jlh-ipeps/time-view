<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller {

  public function viewAction($id, Request $request) {

    $em = $this->getDoctrine()->getManager();
    $book = $em->getRepository('AppBundle:Book')->find($id);
    $mybooks = $em->getRepository('AppBundle:Book')->findAll();
//    dump($mybooks);die();
    $item = "book";
    $tabs = ['gallery','map', 'talk'];

    $title = $book->getTitle();
    
    $pictures = $em->getRepository('AppBundle:Image')->findImagesByBook($id);
        
    
    $image = new Image();
    $form = $this->createForm(ImageType::class, $image);
    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $image->addBook($book);
        $em->persist($book);
        $em->persist($image);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirectToRoute('book', array('id' => 1));
      }
    }
    
    return $this->render('AppBundle:layout:content.html.twig', array(
      'mybooks' => $mybooks,
      'book' => $id,
      'item' => $item,
      'title' => $title,
      'tabs' => $tabs,
      'pictures' => $pictures,
      'form' => $form->createView()
    ));
  }

  public function newAction() {
    $book = new book();
//    $newbook = $this->get('translator')->trans('sidebar.newbook');
//    $book->setTitle($newbook);
    $book->setTitle($this->get('translator')->trans('sidebar.newbook'));

    $em = $this->getDoctrine()->getManager();
    $em->persist($book);
    $em->flush();
    
    return $this->redirectToRoute('book', array('id' => $book->getId()));
  }
 
}
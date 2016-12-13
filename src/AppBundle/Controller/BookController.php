<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\File;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller {

  public function viewAction($id, Request $request) {

    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    
    $em = $this->getDoctrine()->getManager();
    $bookRepo = $em->getRepository('AppBundle:Book');
    $book = $bookRepo->find($id);
    if ($this->getUser() == NULL) {
        $mybooks = NULL;
    } else {
        $mybooks = $bookRepo->findBooksByUser($this->getUser()->getId());
    }


    $item = "book";
    $tabs = ['gallery','map', 'talk'];

    $title = $book->getTitle();
    
    $files = $em->getRepository('AppBundle:File')->findImagesByBook($id);
    
//    dump($files);die();
        
    $file = new File();
    $form = $this->createForm(ImageType::class, $file);
    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $file->addBook($book);
        $em->persist($book);
        $em->persist($file);
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
      'pictures' => $files,
      'form' => $form->createView(),
    ));
  }

  public function newAction() {
    $book = new Book();
    $book->setTitle($this->get('translator')->trans('sidebar.newbook'));
    $book->setUser($this->getUser());
    
    $em = $this->getDoctrine()->getManager();
    $em->persist($book);
    $em->flush();
    
    return $this->redirectToRoute('book', array('id' => $book->getId()));
  }
 
}

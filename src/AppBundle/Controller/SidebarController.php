<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SidebarController extends Controller {

  public function viewAction($id, $originalRequest) {

    $session = $this->get('session');
    $session->set('_locale', $originalRequest->getLocale());
    
    $em = $this->getDoctrine()->getManager();
    $book = $em->getRepository('AppBundle:Book')->find($id);
    if ($this->getUser() == NULL) {
        $mybooks = NULL;
    } else {
        $mybooks = $em->getRepository('AppBundle:Book')->findBooksByUser($this->getUser()->getId());
    }

    return $this->render('AppBundle:layout:sidebar.html.twig', array(
      'mybooks' => $mybooks,
      'book' => $id,
      '_locale' => $session->get('_locale'),
      'originalRequest' => $originalRequest,
    ));
  }

//  public function viewAction($id, Request $request) {
//
//    $session = $this->get('session');
//    $session->set('_locale', $request->getLocale());
//    
//    $em = $this->getDoctrine()->getManager();
//    $book = $em->getRepository('AppBundle:Book')->find($id);
//    if ($this->getUser() == NULL) {
//        $mybooks = NULL;
//    } else {
//        $mybooks = $em->getRepository('AppBundle:Book')->findBooksByUser($this->getUser()->getId());
//    }
//
//    $title = $book->getTitle();
//    
//    
//    return $this->render('AppBundle:layout:sidebar.html.twig', array(
//      'mybooks' => $mybooks,
//      'book' => $id,
//      'title' => $title,
//    ));
//  }
}

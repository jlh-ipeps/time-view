<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PictureController extends Controller
{
  public function viewAction($id, Request $request) {
      
    $em = $this->getDoctrine()->getManager();
    $file = $em->getRepository('AppBundle:File')->find($id);
// to view sidebar 
    $mybooks = $em->getRepository('AppBundle:Book')->findAll();

//    dump($image);die();
    $item = "picture";
    $tabs = ['image','map','albums','talk'];
    
    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    $session->set('picture', $id);
    $session->set('lastURI', $request->getRequestURI());
    
      
    return $this->render('AppBundle:layout:content.html.twig', array(
      'mybooks' => $mybooks,
      'book' => 1, // user variable
      'item' => $item,
      'title' => $file->getAlt(),
      'tabs' => $tabs,
      'picture' => $file->getId()
    ));
  }
}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PictureController extends Controller
{
  public function viewAction($id) {
    $em = $this->getDoctrine()->getManager();
    $file = $em->getRepository('AppBundle:File')->find($id);
// to view sidebar 
    $mybooks = $em->getRepository('AppBundle:Book')->findAll();

//    dump($image);die();
    $item = "picture";
    $tabs = ['image','map','albums','talk'];
    
    
      
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

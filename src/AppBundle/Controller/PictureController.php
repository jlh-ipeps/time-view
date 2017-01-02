<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PictureController extends Controller
{
  public function viewAction($book_id, $file_id, Request $request) {
      
    $em = $this->getDoctrine()->getManager();
    
    $picture = $em->getRepository('AppBundle:Picture')->find(array("book" => $book_id, "file" => $file_id));
    
    $book = $em->getRepository('AppBundle:Book')->find($book_id);
    $file = $em->getRepository('AppBundle:File')->find($file_id);
// to view sidebar 
    $mybooks = $em->getRepository('AppBundle:Book')->findAll();

    
//    dump($image);die();
    $item = "picture";
    $tabs = ['picture','map','books','talk'];
    
    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    $session->set('picture', $file_id);
    $session->set('lastURI', $request->getRequestURI());
    
      
    return $this->render('AppBundle:layout:content.html.twig', array(
        // content
        'item' => $item,
        'title' => $picture->getTitle(),
        'tabs' => $tabs,
        // picture
        'picture' => $picture,
    ));
  }
}

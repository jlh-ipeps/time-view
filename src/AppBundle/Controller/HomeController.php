<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

  public function viewAction($wich, Request $request) {

    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    
    $em = $this->getDoctrine()->getManager();
    $pixRepo = $em->getRepository('AppBundle:Image');
    $pictures = $pixRepo->findAll();
//    dump($pictures);die();

    $item = "home";
    $tabs = ['gallery','map'];

    $title = 'tranlate';
    
    return $this->render('AppBundle:layout:content.html.twig', array(
      'mybooks' => 1,
      'book' => 1,
      'item' => $item,
      'title' => $title,
      'tabs' => $tabs,
      'pictures' => $pictures,
    ));
      
  }
  
}
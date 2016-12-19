<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

  public function viewAction(Request $request) {
      
    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    
    $wich_home = $request->get('wich');
    
//    dump($session->all()    );
    if ($wich_home === 0) {
//        return $this->redirectToRoute('wich_home', array('wich' => 'popular'));
        return $this->redirectToRoute('wich_home', ['request' => $request, 'wich' => 'popular'], 307);
    }
    
    $em = $this->getDoctrine()->getManager();
    $fileRepo = $em->getRepository('AppBundle:File');
//    db request must match $wich_home
    $pictures = $fileRepo->findAll();
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
      'form' => NULL
    ));
      
  }
  
}
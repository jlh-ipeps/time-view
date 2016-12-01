<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SidebarController extends Controller {

  public function viewAction($originalRequest) {

    $session = $this->get('session');
    $session->set('_locale', $originalRequest->getLocale());

//    dump($originalRequest->get('_route_params'), $originalRequest->get('_route_params')['id'], $originalRequest->get('_route'));die();

    if ($originalRequest->get('_route') == 'book') {
        $session->set('book', $originalRequest->get('_route_params')['id']);
    } else {
        $session->set('book', NULL);
    }
    
    $em = $this->getDoctrine()->getManager();
    $bookRepo = $em->getRepository('AppBundle:Book');
    
    if ($this->getUser() == NULL) {
        $mybooks = NULL;
    } else {
        $mybooks = $bookRepo->findBooksByUser($this->getUser()->getId());
    }

    return $this->render('AppBundle:layout:sidebar.html.twig', array(
      'mybooks' => $mybooks,
      'originalRequest' => $originalRequest,
      'session' => $session,
    ));
  }
}

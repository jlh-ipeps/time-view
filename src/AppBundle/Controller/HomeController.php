<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

  public function indexAction(Request $request) {

    $em = $this->getDoctrine()->getManager();
    
    $mybooks = $em->getRepository('AppBundle:Book')->findAll();

    return $this->render('AppBundle:layout:default-content.html.twig', array(
      'mybooks' => $mybooks
      
    ));
  }


}

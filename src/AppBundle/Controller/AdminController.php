<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Picture;
use AppBundle\Entity\File;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Talk;
use AppBundle\Form\ImageType;
use AppBundle\Form\TalkType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends Controller {

  public function viewAction(Request $request) {
     
    $user = $this->getUser();
    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    \Locale::setDefault($request->getLocale());
    
    $em = $this->getDoctrine()->getManager();
    
    $item = "user";
    $tabs = ['admin'];

    
    return $this->render('AppBundle:layout:content.html.twig', array(
        // content
        'item' => $item,
        'tabs' => $tabs,
        'title' => 'admin',
        'formTitle' => NULL,
    ));
  }

}

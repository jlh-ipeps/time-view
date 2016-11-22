<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
  public function viewAction($user) {
    
    $title = "user";
    $tabs = ['profile','talk'];
    
      
    return $this->render('AppBundle:content:content.html.twig', array(
      'title' => $title,
      'subtitle' => $user,
      'tabs' => $tabs
    ));
  }

    public function logoutAction($user) {
    
    $title = "Compte";
    $tabs = ['logout','terms','comments'];
    
      
    return $this->render('AppBundle:layout:content.html.twig', array(
      'title' => $title,
      'subtitle' => $user,
      'tabs' => $tabs
    ));
  }

}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Forms;

class SearchController extends Controller
{
  public function newAction() {
    
    
    
    $title = "search";
    $subtitle = "Liège, Gare";
    $tabs = ['gallery','map','search'];
      
    $pictures = ['3981','4871','5426'];
    
    $formFactory = Forms::createFormFactory();
    $form = $formFactory->createBuilder()
    ->add('search', TextType::class)
    ->add('submit', SubmitType::class)
    ->getForm();
      
    return $this->render('AppBundle:layout:content.html.twig', array(
      'title' => $title,
      'subtitle' => $subtitle,
      'tabs' => $tabs,
        'formTitle' => NULL,
      'pictures' => $pictures
    ));
  }
}

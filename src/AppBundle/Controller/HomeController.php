<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller {

    public function viewAction(Request $request) {
        
        $session = $this->get('session');
        $session->set('_locale', $request->getLocale());

        $em = $this->getDoctrine()->getManager();

        $wich_home = $request->get('wich');
        if ($em->getRepository('AppBundle:Homes')->findByName($wich_home) == []) {
            return $this->redirectToRoute('wich_home', ['request' => $request, 'wich' => 'popular'], 307);
        }
        $findByHome = 'find' . $wich_home . 'Images';
        $pictures = $em->getRepository('AppBundle:Picture')->$findByHome();
        
        // add $pitures to map
        $mapmarkers = array_values(array_filter($pictures, function($p){return $p->getLat();})); 
        // http://stackoverflow.com/questions/13928729/
        $serializer = $this->get('serializer');
        $jsonMapMarkers = $serializer->serialize($mapmarkers, 'json');

        $item = "home";
        $tabs = ['gallery','map'];

        $title = $this->translate('sidebar.' . $wich_home);

        $homeRepo = $em->getRepository('AppBundle:Homes');
        $home_wich = $request->get('_route_params')['wich'];
        $home = $homeRepo->findOneByName($home_wich);
        // ERROR if wich doesn't exist in db !

        if ($home) {
            $session->set('home', $home);
        } 
        $session->set('lastURI', $request->getRequestURI());

        return $this->render('AppBundle:layout:content.html.twig', array(
            // content
            'item' => $item,
            'title' => $title,
            'tabs' => $tabs,
            // home
            'pictures' => $pictures,
            'form' => NULL,
            // map
            'mapjs' => 'map_book.js',
            'maplat' => 50,
            'maplng' => 5,
            'mapmarker' => 0,
            'mapmarkers' => $jsonMapMarkers
        ));
    }
  
  public function hereAction(Request $request) {
      
    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    
    $wich_home = $request->get('wich');
    
    $em = $this->getDoctrine()->getManager();
    $homeRepo = $em->getRepository('AppBundle:Homes');
    $home_wich = $request->get('_route_params')['wich'];
    $home = $homeRepo->findOneByName($home_wich);
    // ERROR if wich doesn't exist in db !
    if ($home) {
        $session->set('home', $home);
    } 
//    dump($session->all(), $wich_home);die();
    if ($wich_home === 0) {
//        return $this->redirectToRoute('wich_home', array('wich' => 'popular'));
        return $this->redirectToRoute('wich_home', ['request' => $request, 'wich' => 'popular'], 307);
    }
    
    $fileRepo = $em->getRepository('AppBundle:File');
//    db request must match $wich_home
    $pictures = $fileRepo->findAll();
//    dump($pictures);die();

    $item = "home";
    $tabs = ['map', 'gallery'];

    $title = $this->translate('sidebar.' . $wich_home);

    
    return $this->render('AppBundle:layout:content.html.twig', array(
        // content
        'item' => $item,
        'title' => $title,
        'tabs' => $tabs,
        // home
        'pictures' => NULL,
        'form' => NULL,
        // map
        'mapjs' => 'map_myhome.js',
        'maplat' => 50,
        'maplng' => 5,
        'mapmarker' => 0,
        'mapmarkers' => null
    ));
  }

  protected function translate($string) {
        $translator = $this->get('translator');
        return $translator->trans($string);

  }
}
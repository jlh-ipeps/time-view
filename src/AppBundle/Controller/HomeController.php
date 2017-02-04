<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\File;

class HomeController extends Controller {

    public function viewAction(Request $request) {
        
        $maxThumbNbr = 100;
        $session = $this->get('session');
        $locale = $request->getLocale();
        $session->set('_locale', $locale);

        $file = new File();
        $uploadDir = $file->getUploadDir() . '/';
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $thumbUrlDir = $imagineCacheManager->getBrowserPath($uploadDir, 'thumb');
//dump($thumbUrlDir);die();

        $em = $this->getDoctrine()->getManager();

        $wich_home = $request->get('wich');
        if ($em->getRepository('AppBundle:Homes')->findByName($wich_home) == []) {
            throw $this->createNotFoundException('This home page does not exist');
//            return $this->redirectToRoute('wich_home', ['request' => $request, 'wich' => 'popular'], 307);
        }
        
        // select pictures by calling $wich_home repository function
        $findByHome = 'find' . $wich_home . 'Images';
        $pictures = $em->getRepository('AppBundle:Picture')->$findByHome($maxThumbNbr);
//dump($pictures);die();
//        $pictures = $em->getRepository('AppBundle:Picture')->$findByHome(
//            $request->query->getInt('page', 1),
//            5
//        );

        // add $pitures to map (serializer do too mmany queries
        $serializer = $this->get('serializer');
        $jsonPictures = $serializer->serialize($pictures, 'json');

        $item = "home";
        $tabs = ['gallery','map_home'];

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
            'locale' => $locale,
            'item' => $item,
            'title' => $title,
            'tabs' => $tabs,
            // home
            'pictures' => $pictures,
            'form' => NULL,
            // map
            'jsonPictures' => $jsonPictures,
            'thumbUrlDir' => $thumbUrlDir,
            'uploadDir' => $uploadDir,
            'maplat' => 50,
            'maplng' => 5,
            'mapmarker' => 0,
        ));
    }

    public function hereAction(Request $request) {
        
        $maxThumbNbr = 100;
        $session = $this->get('session');
        $locale = $request->getLocale();
        $session->set('_locale', $locale);

        $file = new File();
        $uploadDir = $file->getUploadDir() . '/';
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $thumbUrlDir = $imagineCacheManager->getBrowserPath($uploadDir, 'thumb');
//dump($thumbUrlDir);die();

        $em = $this->getDoctrine()->getManager();

        $wich_home = $request->get('wich');
        if ($em->getRepository('AppBundle:Homes')->findByName($wich_home) == []) {
            throw $this->createNotFoundException('This home page does not exist');
//            return $this->redirectToRoute('wich_home', ['request' => $request, 'wich' => 'popular'], 307);
        }


$bounds = array(
    'north' => 51,
    'south' => 49,
    'east' => 6,
    'west' => 4,
); 

        
        // select pictures by calling $wich_home repository function
//        $findByHome = 'find' . $wich_home . 'Images';
//        $pictures = $em->getRepository('AppBundle:Picture')->$findByHome($maxThumbNbr);
        $pictures = $em->getRepository('AppBundle:Picture')->findAjaxImages($bounds, $maxThumbNbr);
//dump($pictures);die();
//        $pictures = $em->getRepository('AppBundle:Picture')->$findByHome(
//            $request->query->getInt('page', 1),
//            5
//        );

        // add $pitures to map (serializer do too mmany queries
        $serializer = $this->get('serializer');
        $jsonPictures = $serializer->serialize($pictures, 'json');

        $item = "home";
        $tabs = ['map_here', 'gallery_here'];

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
            'locale' => $locale,
            'item' => $item,
            'title' => $title,
            'tabs' => $tabs,
            // home
            'pictures' => $pictures,
            'form' => NULL,
            // map
            'jsonPictures' => $jsonPictures,
            'thumbUrlDir' => $thumbUrlDir,
            'uploadDir' => $uploadDir,
            'maplat' => 50,
            'maplng' => 5,
            'mapmarker' => 0,
        ));
    }

    
    public function here2Action(Request $request) {
      
        $maxThumbNbr = 100;
        $session = $this->get('session');
        $locale = $request->getLocale();
        $session->set('_locale', $locale);
    
        $file = new File();
        $uploadDir = $file->getUploadDir() . '/';
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $thumbUrlDir = $imagineCacheManager->getBrowserPath($uploadDir, 'thumb');

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
    
//    db request must match $wich_home
        $pictures = $em->getRepository('AppBundle:Picture')->findAjaxImages($maxThumbNbr);
//        $pictures = $em->getRepository('AppBundle:Picture')->$findByHome($maxThumbNbr);
//dump($pictures);die();
    
        // add $pitures to map (serializer do too mmany queries
        $serializer = $this->get('serializer');
        $jsonPictures = $serializer->serialize($pictures, 'json');

        $item = "home";
        $tabs = ['map_here', 'gallery'];

    $title = $this->translate('sidebar.' . $wich_home);

    
    return $this->render('AppBundle:layout:content.html.twig', array(
            // content
            'locale' => $locale,
            'item' => $item,
            'title' => $title,
            'tabs' => $tabs,
            // home
            'pictures' => $pictures,
            'form' => NULL,
            // map
            'jsonPictures' => $jsonPictures,
            'thumbUrlDir' => $thumbUrlDir,
            'uploadDir' => $uploadDir,
            'maplat' => 50,
            'maplng' => 5,
            'mapmarker' => 0,
        ));
    }

    protected function translate($string) {
        $translator = $this->get('translator');
        return $translator->trans($string);
    }
}
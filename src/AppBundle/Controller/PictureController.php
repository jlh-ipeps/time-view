<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PictureController extends Controller
{
  public function viewAction($book_id, $file_id, Request $request) {
      
    $em = $this->getDoctrine()->getManager();
    
    $picture = $em->getRepository('AppBundle:Picture')->find(array("book" => $book_id, "file" => $file_id));
    $mapLat = null !== $picture->getLat() ? $picture->getLat() : 0;
    $mapLng = null !== $picture->getLng() ? $picture->getLng() : 0;
    $mapMarker = null !== $picture->getLat() ? true : 0;
    
    $book = $em->getRepository('AppBundle:Book')->find($book_id);
//    $file = $em->getRepository('AppBundle:File')->find($file_id);
// to view sidebar 
//    $mybooks = $em->getRepository('AppBundle:Book')->findAll();

    $mapjs = 'map.js';
    if ($book->getUser() == $this->getUser()) {
        $mapjs = 'map_pix.js';
        if ($request->isMethod('POST')) {
            $this->ajaxResponse($request, $em, $picture);
        }
    }
    
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
        // map
        'mapjs' => $mapjs,
        'maplat' => $mapLat,
        'maplng' => $mapLng,
        'mapmaker' => $mapMarker
    ));
    
    
    }

    protected function ajaxResponse($request, $em, $picture) {
        $lat = $request->get('mlat');
        $lng = $request->get('mlng');

        $picture->setLat($lat);
        $picture->setLng($lng);
        $em->persist($picture);
        $em->flush();

        try {
            return new JsonResponse([
                'success' => true,
                'lat' => $lat,
                'lng' => $lng
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }
}

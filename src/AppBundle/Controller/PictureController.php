<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\PictureType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PictureController extends Controller
{
  public function viewAction($book_id, $file_id, Request $request) {
      
    $session = $this->get('session');
    $em = $this->getDoctrine()->getManager();
    
    $picture = $em->getRepository('AppBundle:Picture')->find(array("book" => $book_id, "file" => $file_id));
    if (!$picture) {
        throw $this->createNotFoundException('This picture does not exist');
    }
    $mapLat = null !== $picture->getLat() ? $picture->getLat() : $session->get('latitude');
    $mapLng = null !== $picture->getLng() ? $picture->getLng() : $session->get('longitude');
    $mapMarker = null !== $picture->getLat() ? true : 0;
    
    $book = $em->getRepository('AppBundle:Book')->find($book_id);
//    $file = $em->getRepository('AppBundle:File')->find($file_id);
// to view sidebar 
//    $mybooks = $em->getRepository('AppBundle:Book')->findAll();

    $mapjs = 'map.js';
    if ($book->getUser() == $this->getUser()) {
        $mapjs = 'map_pix.js';
//        if ($request->isMethod('POST')) {
//            $this->ajaxResponse($request, $em, $picture);
//        }
    }
    
//    dump($image);die();
    $item = "picture";
    $tabs = ['picture','map','picture_tag','books'];
    
    $session->set('_locale', $request->getLocale());
    $session->set('picture', $file_id);
    $session->set('lastURI', $request->getRequestURI());
    
    $form = $this->createForm(PictureType::class, $picture);
    $formview = $form->createView();
//        if ($request->isXmlHttpRequest() || $request->isMethod('POST')) {
    if ($request->isXmlHttpRequest()) {
        try {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->persist($picture);
                $em->flush();
            }
            return new JsonResponse([
                'success' => TRUE,
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

    return $this->render('AppBundle:layout:content.html.twig', array(
        // content
        'item' => $item,
        'title' => $picture->getTitle(),
        'tabs' => $tabs,
        // picture
        'picture' => $picture,
        'form' => $formview,
        // map
        'mapjs' => $mapjs,
        'maplat' => $mapLat,
        'maplng' => $mapLng,
        'mapmarker' => $mapMarker,
        'mapmarkers' => NULL
    ));
    
    
    }
    
}

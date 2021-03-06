<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller {
    
    public function geolocationAction(Request $request){
        
        try {
            $bounds = array(
                'north' => $request->get('north'),
                'south' => $request->get('south'),
                'west' => $request->get('west'),
                'east' => $request->get('east'),
            ); 

            $maxThumbNbr = 100;
            $em = $this->getDoctrine()->getManager();
            $pictures = $em->getRepository('AppBundle:Picture')->findAjaxImages($bounds, $maxThumbNbr);
            // add $pitures to map (serializer do too mmany queries
            
            $block = '';
            foreach ($pictures as $picture) {
                $block .= $this->render('AppBundle:ajax:gallery.html.twig', array(
                    'picture' => $picture
                ))->getContent();
            }

            $serializer = $this->get('serializer');
            $jsonPictures = $serializer->serialize($pictures, 'json');

            return new JsonResponse([
                'success' => true,
                'jsonPictures' => $jsonPictures,
                'block' => $block
            ]);

        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }
    
  public function bookAction(Request $request){
    
    $bounds = array(
        'north' => $request->get('nelat'),
        'sud' => $request->get('swlat'),
        'east' => $request->get('nelng'),
        'west' => $request->get('swlng'),
    ); 
    
    $nelat = $request->get('nelat');
    $nelng = $request->get('nelng');
    $swlat = $request->get('swlat');
    $swlng = $request->get('swlng');
    
    $markers = [];
    for( $i = 0; $i<5; $i++ ) {
        $markers[$i] = [
            rand($nelat * 1000, $swlat * 1000) / 1000,
            rand($nelng * 1000, $swlng * 1000) / 1000
        ];
    }
      
    try {

        return new JsonResponse([
            'success' => true,
            'markers' => $markers
        ]);

    } catch (\Exception $exception) {
        return new JsonResponse([
            'success' => false,
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
        ]);
    }
  }

public function pictureAction(Request $request){
    
    $mlat = $request->get('mlat');
    $mlng = $request->get('mlng');
    try {
        return new JsonResponse([
            'success' => true,
            'lat' => $mlat,
            'lng' => $mlng
        ]);
    } catch (\Exception $exception) {
        return new JsonResponse([
            'success' => false,
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage(),
        ]);
    }
}

      
//  
//  
//  public function bookAction($field,$value,$id){
//    //Récupère $book
//    $em = $this->getDoctrine()->getManager();
//    $book = $em->getRepository('AppBundle:Book')->find($id);
//      
//      
//    return;
//  }
//  
  public function ajaxAction($item,$id,$field,$value,Request $request) {

        //Récupère $book
    if (method_exists($item, 'set'.  ucfirst($field))){
      call_user_func([$item,'set'.  ucfirst($field)],$value);
      
      //Persist du book et json_encode (ou jms serialiszer) de la réponse
      
      
    }

    /*if (method_exists($this, $item.'Action')){
      return call_user_func([$this,$item.'Action'],[$field,$request->request->get('value'),$id]);
      
    }
    else{
      throw new NotFoundHttpException();
    }*/
    try {

        return new JsonResponse([
            'success' => true,
            'data'    => ['value'=>$request->request->get('subtitle')] // Your data here
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



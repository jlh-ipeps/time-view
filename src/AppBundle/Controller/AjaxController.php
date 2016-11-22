<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxController extends Controller {
  
  public function bookAction($field,$value,$id){
    //Récupère $book
    $em = $this->getDoctrine()->getManager();
    $book = $em->getRepository('AppBundle:Book')->find($id);
      
      
    return;
  }
  
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



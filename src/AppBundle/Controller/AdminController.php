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
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserInterface;

class AdminController extends Controller {

  public function viewAction($file_id, Request $request) {
     
    $user = $this->getUser();
    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    \Locale::setDefault($request->getLocale());
    
    $maxThumbNbr = 100;
    $em = $this->getDoctrine()->getManager();
    $pictures = $em->getRepository('AppBundle:Picture')->findPicturesForAdmin($maxThumbNbr);
//dump($pictures);die();
    if ($file_id == -1) {
        $file = $pictures[0]->getFile();
    } else {
        $file = $em->getRepository('AppBundle:File')->find($file_id);
    }
    
    $item = "user";
    $tabs = ['admin_gallery', 'admin_picture'];

    
    return $this->render('AppBundle:layout:content.html.twig', array(
        // content
        'item' => $item,
        'tabs' => $tabs,
        'title' => 'admin',
        'formTitle' => NULL,
        // pictures
        'pictures' => $pictures,
        'file' => $file,
    ));
  }

    public function fileAction($file_id, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('AppBundle:File')->find($file_id);
        $em->remove($file);
        $em->flush();

        
        return $this->redirectToRoute('admin');
        
    }
     
    public function userAction($user_id, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        $user = $em->getRepository('AppBundle:User')->find($user_id);
        $user->setEnabled(0);
        
        $em->persist($user);
        $em->flush();
     
        
//        $userManager->deleteUser($user);
        
        return $this->redirectToRoute('admin');
        
    }
     

}

<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;

class SidebarController extends Controller {

    public function viewAction($originalRequest) {
        
        
        $user = $this->getUser();
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        
        $homeRepo = $em->getRepository('AppBundle:Homes');
        $homes = $homeRepo->findAll();
        
        $localeRepo = $em->getRepository('AppBundle:Languages');
        $locales = $localeRepo->findAll();
        
        # set default value
        $mybooks = [];
        if (!$session->get('home')) {
            $session->set('book', NULL);
            $session->set('home', $homeRepo->find(1));
        }
            
        if ($user) {
            $bookRepo = $em->getRepository('AppBundle:Book');
            $mybooks = $bookRepo->findBooksByUser($this->getUser()->getId());

            $homesRepo = $em->getRepository('AppBundle:Homes');
            $sessionRepo = $em->getRepository('AppBundle:LastSession');

            $lastSession = $sessionRepo->find($user->getSession());
            $lastSession->setHome($homesRepo->find($session->get('home')->getId()));
//            $lastSession->setHome($session->get('home'));
            $lastSession->setBook($session->get('book'));
            $lastSession->setUri($session->get('lastURI'));

            $em->persist($lastSession);
            $em->flush();
        }

        return $this->render('AppBundle:layout:sidebar.html.twig', array(
            'locales' => $locales,
            'mybooks' => $mybooks,
            'homes' => $homes,
            'originalRequest' => $originalRequest,
            'session' => $session,
        ));
    }

//    protected function isMyBook($book, $mybooks, $session) {
//        if (in_array($book, array_map(function($b){return $b->getId();},$mybooks))) {
//            return $book;
//        } else {
//            return $session->get('book');
//        }
//    }
    
}

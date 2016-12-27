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
        
        $mybooks = [];
        if ($user) {
            
            $bookRepo = $em->getRepository('AppBundle:Book');
            $mybooks = $bookRepo->findBooksByUser($this->getUser()->getId());
            
//            $sessionRepo = $em->getRepository('AppBundle:LastSession');
//            $lastSession = $sessionRepo->find($user->getSession());
//            
//            $session->set('book', $lastSession->getBook());
//            $session->set('home', $lastSession->getHome());

//        dump($session);die();
        }

        if (!$session->get('home')) {
            # set default value
            $session->set('book', NULL);
            $session->set('home', $homeRepo->find(1));
        }
            
        switch ($originalRequest->get('_route')) {
            case 'book':
                $book = $originalRequest->get('_route_params')['id'];
                # if book is mine
                $session->set('book', $this->isMyBook($book, $mybooks, $session));
//                dump($session->get('book'));die();
                $session->set('lastURI', $originalRequest->getRequestURI());
//        dump($originalRequest);die();
//        dump($originalRequest->getRequestURI());die();
                break;
            case 'wich_home':
                $home_wich = $originalRequest->get('_route_params')['wich'];
                $home = $homeRepo->findOneByName($home_wich);
/* ERROR if wich doesn't exist in db ! */
                $session->set('home', $home);
                $session->set('lastURI', $originalRequest->getRequestURI());
                break;
            case 'home_here':
                $home_wich = $originalRequest->get('_route_params')['wich'];
                $home = $homeRepo->findOneByName($home_wich);
/* ERROR if wich doesn't exist in db ! */
                $session->set('home', $home);
                $session->set('lastURI', $originalRequest->getRequestURI());
                break;
            case 'picture':
                $session->set('picture', $originalRequest->get('_route_params')['id']);
                $session->set('lastURI', $originalRequest->getRequestURI());
                break;
        }
        
        if ($user) {
            $sessionRepo = $em->getRepository('AppBundle:LastSession');
            $lastSession = $sessionRepo->find($user->getSession());
            $lastSession->setHome($session->get('home'));
            $lastSession->setBook($session->get('book'));
            $lastSession->setUri($session->get('lastURI'));

        dump($session->get('home'));
//        die();

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

    protected function isMyBook($book, $mybooks, $session) {
        if (in_array($book, array_map(function($b){return $b->getId();},$mybooks))) {
            return $book;
        } else {
            return $session->get('book');
        }
    }
    
}

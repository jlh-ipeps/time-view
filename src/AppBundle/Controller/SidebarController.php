<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\MySession;

class SidebarController extends Controller {

    public function viewAction($originalRequest) {
        
        $user = $this->getUser();
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        
        $homeRepo = $em->getRepository('AppBundle:Homes');
        $homes = $homeRepo->findAll();
        
        $localeRepo = $em->getRepository('AppBundle:Languages');
        $locales = $localeRepo->findAll();


        if ($user) {
            $sessionRepo = $em->getRepository('AppBundle:MySession');
            $mySession = $sessionRepo->find($user->getSession()->getId());

//            $myLanguage = $mySession->getLocale()->getIso();
//            dump($user, $mySession, $myLanguage);
//            die();
        } else {
            $mySession = new MySession();
        }
        
//        if (!$this->getUser()) {
//            $defaultSession = $session;
//            $defaultSession->set('_locale', $originalRequest->getLocale());
//            $defaultSession->set('book', NULL);
//            $defaultSession->set('picture', NULL);
//            $defaultSession->set('mybooks', NULL);
//            $defaultSession->set('mysearches', NULL);
//            $defaultSession->set('home', 'popular');
//
//            $session = $defaultSession;
//        }
        
        $locale = $mySession->getLocale();
        $book = $mySession->getBook();
        $wich_home = $mySession->getHome();
        
//        dump($user->getSession(), $locale, $book, $wich_home);
//        die();

        switch ($originalRequest->get('_route')) {
            case 'book':
                $book = $originalRequest->get('_route_params')['id'];
                $session->set('book', $book);
                $mySession->setBook($book);
                break;
            case 'wich_home':
                $wich_home = $originalRequest->get('_route_params')['wich'];
                $session->set('home', $wich_home);
                break;
            case 'picture':
                $session->set('picture', $originalRequest->get('_route_params')['id']);
                break;
        }
        
        if ($user) {
            $em->persist($mySession);
            $em->flush();
        }

        $mybooks = NULL;
        $bookRepo = $em->getRepository('AppBundle:Book');
        if ($this->getUser()) {
            $mybooks = $bookRepo->findBooksByUser($this->getUser()->getId());
        }

        return $this->render('AppBundle:layout:sidebar.html.twig', array(
            'locales' => $locales,
            'mybooks' => $mybooks,
            'book' => $book,
            'wich_home' => $wich_home,
            'homes' => $homes,
            'originalRequest' => $originalRequest,
            'session' => $session,
        ));
    }
}

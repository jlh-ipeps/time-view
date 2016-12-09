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
        
        
//        if ($mySession->getLocale()) {
//            $originalRequest->setLocale($mySession->getLocale()->getIso());
////            dump($session, $mySession->getLocale()->getIso(), $originalRequest->getLocale());die();
//        }


        $book = $mySession->getBook();
        $session->set('book', $book);
        $home_id = $mySession->getHome();

        switch ($originalRequest->get('_route')) {
            case 'book':
                $book = $originalRequest->get('_route_params')['id'];
                $session->set('book', $book);
                $mySession->setBook($book);
                break;
            case 'wich_home':
                $home_wich = $originalRequest->get('_route_params')['wich'];
//                $home_wich = $homeRepo->findOneByName($originalRequest->get('_route_params')['wich']);
                $home_id = $homeRepo->findOneByName($home_wich);
//            dump($originalRequest, $home_wich, $originalRequest->get('_route_params')['wich']);die();
                $session->set('home', $home_id);
                $mySession->setHome($home_id);
                break;
            case 'picture':
                $session->set('picture', $originalRequest->get('_route_params')['id']);
                break;
        }
//        dump($originalRequest);die();
        
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
            'wich_home' => $home_id,
            'homes' => $homes,
            'originalRequest' => $originalRequest,
            'session' => $session,
        ));
    }
}

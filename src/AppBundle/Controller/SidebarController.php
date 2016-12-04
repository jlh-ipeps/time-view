<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SidebarController extends Controller {

    public function viewAction($originalRequest) {
        
        $homes = array(
            'popular',
            'new',
            'here',
            'random'
        );
      
        $session = $this->get('session');
        
        if (!$this->getUser()) {
            $defaultSession = $session;
            $defaultSession->set('_locale', $originalRequest->getLocale());
            $defaultSession->set('book', NULL);
            $defaultSession->set('picture', NULL);
            $defaultSession->set('mybooks', NULL);
            $defaultSession->set('mysearches', NULL);
            $defaultSession->set('home', 'popular');

            $session = $defaultSession;
        }

        
        switch ($originalRequest->get('_route')) {
            case 'book':
                $session->set('book', $originalRequest->get('_route_params')['id']);
                break;
            case 'picture':
                $session->set('picture', $originalRequest->get('_route_params')['id']);
                break;
            case 'home':
                $session->set('home', $originalRequest->get('_route_params')['wich']);
                break;
        }


//        dump(
//                $originalRequest->get('_route_params'), 
////                $originalRequest->get('_route_params')['id'], 
//                $originalRequest->get('_route'),
//                $session,
//                $session->get('_locale'),
//                $session->get('book'),
//                $session->get('mybooks'),
//                $session->get('mysearches'),
//                $session->get('picture'),
//                $session->get('home'),
//                $this->getUser()
//            );
//        die();

        if ($originalRequest->get('_route') == 'book') {
            $session->set('book', $originalRequest->get('_route_params')['id']);
        } else {
            $session->set('book', NULL);
        }

        $em = $this->getDoctrine()->getManager();
        $bookRepo = $em->getRepository('AppBundle:Book');
        if ($this->getUser() == NULL) {
            $mybooks = NULL;
        } else {
            $mybooks = $bookRepo->findBooksByUser($this->getUser()->getId());
        }

        return $this->render('AppBundle:layout:sidebar.html.twig', array(
            "locales" => array(
                'de' => "Deutch",
                'en' => "English",
                'fr' => "Français",
                'nl' => "Nederlands"
            ),
            'mybooks' => $mybooks,
            'originalRequest' => $originalRequest,
            'session' => $session,
            'homes' => $homes,
        ));
    }
}

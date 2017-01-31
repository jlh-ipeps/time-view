<?php

namespace UserBundle\Listener;

use Symfony\Component\EventDispatcher\Event;

class SessionListener {
    
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function updateSession(Event $event) {


        $token_storage = $this->container->get('security.token_storage');
        $session = $this->container->get('session');
        $user = $token_storage->getToken()->getUser();

//        dump($user, $session);die();

        $session->set('accountMenu', '123');
//  $session->save();

        $sideBar = array(
            'account' => 'route'
        );
        $test = $sideBar['account'];

        $test2 = (object) $sideBar;
        
        $test3 = json_encode($sideBar);
        
        $request = $event->getRequest();
        // Matched route
        $_route  = $request->attributes->get('_route');
        // Matched controller
        $_controller = $request->attributes->get('_controller');
        // All route parameters including the `_controller`
        $params      = $request->attributes->get('_route_params');

//        dump($sideBar, $test, $test2, $test3, $session, $_route, $_controller, $params, $request);die();

        $em=$this->container->get('doctrine')->getEntityManager();
        $sessionRepo = $em->getRepository('AppBundle:LastSession');
        $lastSession = $sessionRepo->find($user->getSession());
        
        
        dump($lastSession->getBook(), $lastSession, $user, $session);
        
        die();


//        dump($sideBar, $test, $test2, $test3, $session, $_route, $_controller, $params, $request);die();

    }
    
}
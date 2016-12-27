<?php

namespace UserBundle\Handler;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Response;

class LogoutHandler implements LogoutHandlerInterface {
    
    protected $em;
	protected $session;
	protected $router;
	
	public function __construct(Router $router, Session $session, EntityManager $em) {
        
		$this->session = $session;
		$this->router = $router;
        $this->em = $em;
	}
	
	public function logout(Request $request, Response $response, TokenInterface $token) {
		
        $session = $this->session;
        
//        dump($session);die();
        $em = $this->em;
        $user = $token->getUser();

//        $sessionRepo = $em->getRepository('AppBundle:LastSession');
//        $lastSession = $sessionRepo->find($user->getSession());
//        
//        $lastSession->setBook($session->get('book'));
//        $lastSession->setHome($session->get('home'));
//        $lastSession->setUri($session->get('lastURI'));
//        $em->persist($lastSession);
//        $em->flush();


	}
	
}
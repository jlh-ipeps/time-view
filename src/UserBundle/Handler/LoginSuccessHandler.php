<?php

namespace UserBundle\Handler;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected $tokenstorage;
    protected $em;
	protected $session;
	protected $router;
	protected $userManager;
	
	public function __construct(Router $router, UserManagerInterface $userManager, Session $session, EntityManager $em) {
        
		$this->session = $session;
		$this->router = $router;
		$this->userManager = $userManager;
        $this->em = $em;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
		
//		if ($this->security->isGranted('ROLE_SUPER_ADMIN'))
//		{
//			$response = new RedirectResponse($this->router->generate('category_index'));			
//		}
//		elseif ($this->security->isGranted('ROLE_ADMIN'))
//		{
//			$response = new RedirectResponse($this->router->generate('category_index'));
//		} 
//		elseif ($this->security->isGranted('ROLE_USER'))
//		{
//			// redirect the user to where they were before the login process begun.
//			$referer_url = $request->headers->get('referer');
//						
//			$response = new RedirectResponse($referer_url);
//		}

        $session = $this->session;
        $em = $this->em;
        $user = $token->getUser();

        $sessionRepo = $em->getRepository('AppBundle:LastSession');
        $lastSession = $sessionRepo->find($user->getSession());

        $session->set('book', $lastSession->getBook());
        $session->set('home', $lastSession->getHome());

        if ($session->get('lastURI')) {
            $url = $session->get('lastURI');
        } else {
			$url = $request->headers->get('referer');
        }
        
    	$response = new RedirectResponse($url);
		return $response;
	}
	
}
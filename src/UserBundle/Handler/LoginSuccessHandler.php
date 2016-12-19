<?php

namespace UserBundle\Handler;

use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
	
	protected $router;
	protected $userManager;
	
	public function __construct(Router $router, UserManagerInterface $userManager)
	{
		$this->router = $router;
		$this->userManager = $userManager;
	}
	
	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		
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

        $user = $token->getUser();
        $home = $user->getSession()->getHome();
//        dump($request, $user);
        
			$referer_url = $request->headers->get('referer');
						
			$response = new RedirectResponse($referer_url);

		return $response;
	}
	
}
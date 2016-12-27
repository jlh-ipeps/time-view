<?php

namespace UserBundle\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface {
	
	public function onLogoutSuccess(Request $request) {
		$referer_url = $request->headers->get('referer');
        
		$response = new RedirectResponse($referer_url);		
		return $response;
	}
}
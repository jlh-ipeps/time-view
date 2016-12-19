<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UserBundle\Handler;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
	
	protected $router;
	
	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	public function onLogoutSuccess(Request $request)
	{
		// redirect the user to where they were before the login process begun.
		$referer_url = $request->headers->get('referer');

//        $home = $request->getSession();
//        dump($request, $home);die();
        
        $url = '/web/app_dev.php/en/book/14/';
					
		$response = new RedirectResponse($url);		
//		$response = new RedirectResponse($referer_url);		
		return $response;
	}
	
}
<?php

namespace UserBundle\Listener;

use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityListener
{

   public function __construct(AuthorizationChecker $security, TokenStorage $token, Session $session)
   {
      $this->security = $security;
      $this->token = $token;
      $this->session = $session;
   }

   public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
   {
        $user = $this->token->getToken()->getUser();
        
//        dump($event);die();
   }

}
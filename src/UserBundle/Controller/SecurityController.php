<?php
// src/UserBundle/Controller/SecurityController.php
namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\SecurityController as BaseController;

class SecurityController extends BaseController
{
    public function loginAction(Request $request) {
        $response = parent::loginAction($request);
        return $response;
    }
    
    protected function renderLogin(array $data) {
        
        $data['tabs'] = ['login'];
        $data['item'] = 'user';
        $data['title'] = 'user';
        $data['formTitle'] = NULL;

        return $this->render('AppBundle:layout:content.html.twig', $data);
    }

    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }

}

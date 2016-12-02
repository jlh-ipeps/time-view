<?php
// src/UserBundle/Controller/RegistrationController.php
namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

class RegistrationController extends BaseController
{
    public function registerAction(Request $request)
    {
//      dump($request);die();
        $response = parent::registerAction($request);
//      dump($response);die();

        // ... do custom stuff
        return $response;
    }
}
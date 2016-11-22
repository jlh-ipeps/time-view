<?php
// src/UserBundle/Controller/ProfileController.php
namespace UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;


use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    public function showAction()
    {
        
                
        $em = $this->getDoctrine()->getManager();
        $mybooks = $em->getRepository('AppBundle:Book')->findAll();
        $tabs = ['profile','talk'];
            


        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('AppBundle:layout:content.html.twig', array(
//        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'mybooks' => $mybooks,
            'book' => 1, // session variable
            'item' => 'user',
            'title' => $user->getUsername(),
            'tabs' => $tabs,
            'user' => $user,
        ));

//        $response = parent::showAction();
//
//        // ... do custom stuff
//        return $response;
    }
}
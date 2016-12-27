<?php
// src/UserBundle/Controller/ProfileController.php
namespace UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    public function showAction() {
                
        $em = $this->getDoctrine()->getManager();
        $mybooks = $em->getRepository('AppBundle:Book')->findAll();
        $tabs = ['profile','talk'];

        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('AppBundle:layout:content.html.twig', array(
            'item' => 'user',
            'title' => $user->getUsername(),
            'tabs' => $tabs,
            'user' => $user,
        ));

    }
}
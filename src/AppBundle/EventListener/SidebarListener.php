<?php

namespace AppBundle\EventListener;

/**
 * Description of SidebarListener
 *
 * @author ipeps
 */

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class SidebarListener {

    public function processBeta(FilterResponseEvent $event) {

        if (!$event->isMasterRequest()) {
            return;
        }
        
        $response = $event->getResponse();

        $event->setResponse($response);
    }
}

<?php

namespace UserBundle\Listener;

use AppBundle\Entity\LastSession;

class PrePersistListener {
    
    public function prePersist($arg) {

//        dump($arg);die();
        $entity = $arg->getEntity();
        $em = $arg->getEntityManager();
        
        $entityName = $em->getClassMetadata(get_class($entity))->getName();

        if ($entityName == 'AppBundle\Entity\User' && $entity->getId() == NULL) {

            $english = $em->getRepository('AppBundle:Languages')->findOneByIso('en');
            $home = $em->getRepository('AppBundle:Homes')->findOneByName('popular');
    //        dump($english);die();

            $lastSession = new LastSession();
            $lastSession->setLocale($english);
            $lastSession->setHome($home);
            $entity->setSession($lastSession);

            
//            dump($user, $entity, $arg, $lastSession);die();
        }

    }
    
}
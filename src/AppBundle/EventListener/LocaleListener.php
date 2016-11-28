<?php
// src/AppBundle/EventListener/LocaleListener.php
namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        $newLocale = $request->request->get('choose_locale');
//        dump($request);die();
//        dump($request->request->get('choose_locale'));die();

        // try to see if the locale has been set as a _locale routing parameter
        if ($newLocale) {
            $request->setLocale($newLocale['locale']);
            $request->getSession()->set('_locale', $newLocale['locale']);
//        dump($request->getLocale(), $newLocale['locale'], $request);die();
        } elseif ($locale = $request->attributes->get('_locale')) {
//        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        } else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
//        dump($request->attributes->get('_locale'));die();
//        dump($newLocale['locale']);die();
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered after the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 15)),
        );
    }
}
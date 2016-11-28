<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use AppBundle\Form\ChooseLocaleType;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller {

  public function viewAction($id, Request $request) {

//        dump($request);die();
//        dump($request->request->get('choose_locale'));die();

    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
//        dump($request->getLocale());die();

//    $newLocale = $request->request->get('choose_locale');
//    if ($newLocale) {
//        $request->setLocale($newLocale['locale']);
//        $session->set('_locale', $newLocale['locale']);
//    }
//    dump($newLocale['locale']);die();

//    $request->setLocale('fr');
//    $locale = $request->getLocale();

    $locale = $session->get('locale');
    
    $em = $this->getDoctrine()->getManager();
    $book = $em->getRepository('AppBundle:Book')->find($id);
    if ($this->getUser() == NULL) {
        $mybooks = NULL;
    } else {
        $mybooks = $em->getRepository('AppBundle:Book')->findBooksByUser($this->getUser()->getId());
    }

    $localeForm = $this->createForm(ChooseLocaleType::class, array('locale' => $request->getLocale()));


    $item = "book";
    $tabs = ['gallery','map', 'talk'];

    $title = $book->getTitle();
    
    $pictures = $em->getRepository('AppBundle:Image')->findImagesByBook($id);
        
    $image = new Image();
    $form = $this->createForm(ImageType::class, $image);
    if ($request->isMethod('POST')) {
      $form->handleRequest($request);
      if ($form->isValid()) {
        $image->addBook($book);
        $em->persist($book);
        $em->persist($image);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
        return $this->redirectToRoute('book', array('id' => 1));
      }
    }
    
    return $this->render('AppBundle:layout:content.html.twig', array(
      'mybooks' => $mybooks,
      'book' => $id,
      'item' => $item,
      'title' => $title,
      'tabs' => $tabs,
      'pictures' => $pictures,
      'form' => $form->createView(),
      'localeForm' => $localeForm->createView(),
      'locale' => $locale,
    ));
  }

  public function newAction() {
    $book = new Book();
    $book->setTitle($this->get('translator')->trans('sidebar.newbook'));
    $book->setUser($this->getUser());
    
    $em = $this->getDoctrine()->getManager();
    $em->persist($book);
    $em->flush();
    
    return $this->redirectToRoute('book', array('id' => $book->getId()));
  }
 
}

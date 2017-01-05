<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Picture;
use AppBundle\Entity\File;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;
use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\Base\Coordinate;

class BookController extends Controller {

  public function viewAction($book_id, Request $request) {

    $session = $this->get('session');
    $session->set('_locale', $request->getLocale());
    
    $em = $this->getDoctrine()->getManager();
    $bookRepo = $em->getRepository('AppBundle:Book');
    $book = $bookRepo->find($book_id);
    if ($this->getUser() == NULL) {
        $mybooks = NULL;
    } else {
        $mybooks = $bookRepo->findBooksByUser($this->getUser()->getId());
    }


    $item = "book";
    $tabs = ['gallery','map', 'talk'];
    
    $pictures = $em->getRepository('AppBundle:File')->findImagesByBook($book_id);
    
                
    $session->set('lastURI', $request->getRequestURI());

    if ($book->getUser() == $this->getUser()) {
        $session->set('book', $this->isMyBook($book_id, $mybooks, $session));
        $file = new File();
        $form = $this->createForm(ImageType::class, $file);
        $formview = $form->createView();
        if ($request->isMethod('POST')) {
            $picture = new Picture();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $file->addPicture($picture);
                $picture->setBook($book);
                $picture->setFile($file);
                $em->persist($picture);
                $em->persist($file);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
                return $this->redirectToRoute('picture', array('book_id' => $book_id, 'file_id' => $picture->getFile()->getId()));
            }
        }
    } else {
        $formview = NULL;
    }
    

    $map = new Map();
    $map->setVariable('map');
    
//$map = $this->get('ivory_google_map.map');
    $map->setAutoZoom(false);
    $map->setCenter(new Coordinate(5, 50));
    $map->setMapOption('zoom', 7);
    
    return $this->render('AppBundle:layout:content.html.twig', array(
        // content
        'item' => $item,
        'title' => $book->getTitle(),
        'tabs' => $tabs,
        // picture
        'book_id' => $book_id,
        'pictures' => $pictures,
        'form' => $formview,
        'map' => $map
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

    protected function isMyBook($book_id, $mybooks, $session) {
//        dump($book_id, $mybooks, $session);die();
        if (in_array($book_id, array_map(function($b){return $b->getId();},$mybooks))) {
            return $book_id;
        } else {
            return $session->get('book');
        }
    }

}

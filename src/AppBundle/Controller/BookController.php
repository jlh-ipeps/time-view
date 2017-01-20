<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Picture;
use AppBundle\Entity\File;
use AppBundle\Entity\Tag;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    
    $serializer = $this->get('serializer');
    $jsonTags = $serializer->serialize($book->getTags(), 'json');


    $item = "book";
    $tabs = ['gallery','map', 'tag', 'talk'];
    
    $pictures = $em->getRepository('AppBundle:Picture')->findPicturesByBook($book_id);
    
    // add $pitures to map
    $mapmarkers = array_values(array_filter($pictures, function($p){return $p->getLat();})); 
    $jsonMapMarkers = $serializer->serialize($mapmarkers, 'json');

                
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
    
    
    return $this->render('AppBundle:layout:content.html.twig', array(
        // content
        'item' => $item,
        'title' => $book->getTitle(),
        'tabs' => $tabs,
        // gallery
        'pictures' => $pictures,
        'form' => $formview,
        //tags
        'listTags' => $jsonTags,
        // map
        'mapjs' => 'map_book.js',
        'maplat' => 50,
        'maplng' => 5,
        'mapmarker' => 0,
        'mapmarkers' => $jsonMapMarkers
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
    
    
    public function tagAction($book_id, Request $request) {

        $action = $request->get('action');        
        $tag = $request->get('tag');
        
        switch ($action) {
            case 'beforeItemAdd':
                return $this->addTag($book_id, $tag);
            case 'itemRemoved':
                return $this->removeTag($book_id, $tag);
        }
    }
    
    protected function removeTag($book_id, $tag) {
        try {
            $em = $this->getDoctrine()->getManager();
            $bookRepo = $em->getRepository('AppBundle:Book');
            $tagRepo = $em->getRepository('AppBundle:Tag');
            $tagEntity = $tagRepo->findOneByTagName($tag);
            $book = $bookRepo->find($book_id);
            $book->removeTag($tagEntity);
            $em->persist($book);
            $em->flush();
            return new JsonResponse([
                'success' => 'ok',
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function addTag($book_id, $tag) {
        try {
            $em = $this->getDoctrine()->getManager();
            $bookRepo = $em->getRepository('AppBundle:Book');
            $book = $bookRepo->find($book_id);
            $tagRepo = $em->getRepository('AppBundle:Tag');
            $tagEntity = $tagRepo->findOneByTagName($tag);
            if (!$tagEntity) {
                $tagEntity = new Tag;
                $tagEntity->setTagname($tag);
            }
            $book->addTag($tagEntity);
            $em->persist($book);
            $em->flush();
            return new JsonResponse([
                'success' => 'ok',
            ]);
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
    }

}

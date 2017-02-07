<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Picture;
use AppBundle\Entity\File;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Talk;
use AppBundle\Form\ImageType;
use AppBundle\Form\TalkType;
use AppBundle\Form\BookTitleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookController extends Controller {

    public function viewAction($book_id, Request $request) {

        $maxThumbNbr = 100;

        $user = $this->getUser();
        $session = $this->get('session');
        $locale = $request->getLocale();
        $session->set('_locale', $locale);
        \Locale::setDefault($request->getLocale());
        
        $translator = $this->get('translator');
    
        $file = new File();
        $uploadDir = $file->getUploadDir() . '/';
        $imagineCacheManager = $this->get('liip_imagine.cache.manager');
        $thumbUrlDir = $imagineCacheManager->getBrowserPath($uploadDir, 'thumb');

    
        $em = $this->getDoctrine()->getManager();
        $bookRepo = $em->getRepository('AppBundle:Book');
        $book = $bookRepo->find($book_id);
        // sidebar books
        if ($this->getUser() == NULL) {
            $mybooks = NULL;
        } else {
            $mybooks = $bookRepo->findBooksByUser($user->getId());
        }
    
    
        $talk = new Talk();
        $talk_form = $this->createForm(TalkType::class, $talk);
        $talk_formview = $talk_form->createView();
//        if ($request->isMethod('POST')) {
//            $talk_form->handleRequest($request);
//            if ($talk_form->isValid()) {
//                $talk->setUser($user);
//                $talk->setBook($book);
//                $em->persist($talk);
//                $em->flush();
//            }
//        }
        $talkRepo = $em->getRepository('AppBundle:Talk');
        $talks = $talkRepo->findMessagesByBook($book_id);

//        $tooManyQueries = $book->getTags();
        $tooManyQueries = $bookRepo->findTagsByBook($book_id);
    

        $item = "book";
        $tabs = ['gallery','map_book', 'tag', 'talk'];
    
        $pictures = $em->getRepository('AppBundle:Picture')->findPicturesByBook($book_id, $maxThumbNbr);
//dump($pictures);die();
//dump($pictures[2]);die();
//dump($pictures[2][0]->getFile());die();
//dump($pictures[2][0]->getLat());die();

        // add $pitures to map
        $serializer = $this->get('serializer');
        $jsonPictures = $serializer->serialize($pictures, 'json');
//        dump($jsonPictures);die();

//    
//    $bounds = array(
//        'south' => 0,
//        'west' => 0,
//        'north' => 0,
//        'east' => 0,
//    );
//    $mapmarkers = [];
//    for ($i = 0; $i < count($pictures); ++$i) {
//        if ($pictures[$i]['lat']) {
//            $mapmarkers[$i] = $pictures[$i];
//            $bounds['south'] = min(array_filter(array($bounds['south'], $pictures[$i]['lat'])));
//            $bounds['west'] = min(array_filter(array($bounds['west'], $pictures[$i]['lng'])));
//            $bounds['north'] = max(array_filter(array($bounds['north'], $pictures[$i]['lat'])));
//            $bounds['east'] = max(array_filter(array($bounds['east'], $pictures[$i]['lng'])));
//        }
//    }
//
//    // add $pitures to map if lat is defined
////    $mapmarkers = array_values(array_filter($pictures, function($p){return $p['lat'];})); 
//    $serializer = $this->get('serializer');
//    $jsonMapMarkers = $serializer->serialize($mapmarkers, 'json');
////dump($pictures, $mapmarkers);die();

        $session->set('lastURI', $request->getRequestURI());
        $formTitleView = NULL;
        if ($book->getUser() == $this->getUser()) {
            $session->set('book', $book_id);
            $formTitle = $this->createForm(BookTitleType::class, $book);
            $formTitleView = $formTitle->createView();
            $form = $this->createForm(ImageType::class, $file, array());
            $formview = $form->createView();
            if ($request->isMethod('POST')) {
                $picture = new Picture();
                $form->handleRequest($request);
                $formTitle->handleRequest($request);
                    if ($formTitle->isValid()) {
                        $em->persist($book);
                        $em->flush();
                        return $this->redirectToRoute('book', array('book_id' => $book->getId()));
                    }
                    if ($form->isValid()) {
                        $file->addPicture($picture);
                        $file->setUser($user);
                        $picture->setBook($book);
                        $picture->setFile($file);
                        $em->persist($picture);
                        $em->persist($file);
                        try {
                            $em->flush();
                        } catch (\Exception $exc) {
                            $session->getFlashBag()->add('error', $translator->trans('error.upload'));
                            return $this->redirectToRoute('book', array('book_id' => $book->getId()));
                        }
                        return $this->redirectToRoute('picture', array('book_id' => $book_id, 'file_id' => $picture->getFile()->getId()));
                    } else {
                        $errorMsg = $form->get('file')->getErrors()[0]->getMessage();
                        $session->getFlashBag()->add('error', $errorMsg);
                        return $this->redirectToRoute('book', array('book_id' => $book->getId()));
                    }
            }
        } else {
            $formview = NULL;
        }
    
        return $this->render('AppBundle:layout:content.html.twig', array(
            // content
            'locale' => $locale,
            'item' => $item,
            'title' => $book->getTitle(),
            'tabs' => $tabs,
            'formTitle' => $formTitleView,
            // gallery
            'pictures' => $pictures,
            'form' => $formview,
            //tags
            'repoTags' => $tooManyQueries,
            // talk
            'talks' => $talks,
            'talk_form' => $talk_formview,
            // map
            'jsonPictures' => $jsonPictures,
            'thumbUrlDir' => $thumbUrlDir,
            'uploadDir' => $uploadDir,
            'maplat' => 50,
            'maplng' => 5,
            'mapmarker' => 0
        ));
    }

    public function newAction() {
        $book = new Book();
        $book->setTitle($this->get('translator')->trans('sidebar.newbook'));
        $book->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();
        return $this->redirectToRoute('book', array('book_id' => $book->getId()));
    }

    public function ajaxAction($book_id, Request $request) {

        // to be done again
        $action = $request->get('action');
        
        if ($action) {
            $tag = $request->get('tag');
            switch ($action) {
                case 'beforeItemAdd':
                    return $this->addTag($book_id, $tag);
                case 'itemRemoved':
                    return $this->removeTag($book_id, $tag);
            }
        } else {
            return $this->addMessage($book_id, $request);
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

    public function addMessage($book_id, $request) {
        try {
            $em = $this->getDoctrine()->getManager();
            $bookRepo = $em->getRepository('AppBundle:Book');
            $book = $bookRepo->find($book_id);
            $talk = new Talk();
            $talk_form = $this->createForm(TalkType::class, $talk);
            $talk_form->handleRequest($request);
            if ($talk_form->isValid()) {
                $talk->setUser($this->getUser());
                $talk->setBook($book);
                $em->persist($talk);
                $em->flush();
            }
            $talk->{'username'} = $talk->getUser()->getUsername();
            $block = $this->render('AppBundle:ajax:talk.html.twig', array(
                'talk' => $talk
            ))->getContent();
            return new JsonResponse([
                'success' => 'ok',
                'block' => $block
            ]);
        } catch (Exception $ex) {
            return new JsonResponse([
                'success' => false,
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);
        }
        
    }

}

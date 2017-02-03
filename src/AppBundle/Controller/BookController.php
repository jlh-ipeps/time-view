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
        if ($request->isMethod('POST')) {
            $talk_form->handleRequest($request);
            if ($talk_form->isValid()) {
                $talk->setUser($user);
                $talk->setBook($book);
                $em->persist($talk);
                $em->flush();
            }
        }
        $talkRepo = $em->getRepository('AppBundle:Talk');
        $talks = $talkRepo->findMessagesByBook($book_id);

//        $tooManyQueries = $book->getTags();
        $tooManyQueries = $bookRepo->findTagsByBook($book_id);
    

        $item = "book";
        $tabs = ['gallery','map_book', 'tag', 'talk'];
    
        $pictures = $em->getRepository('AppBundle:Picture')->findPicturesByBook($book_id, $maxThumbNbr);

        // add $pitures to map
        $mapmarkers = array_values(array_filter($pictures, function($p){return $p['lat'];})); 
        // http://stackoverflow.com/questions/13928729/
        $serializer = $this->get('serializer');
        $jsonMapMarkers = $serializer->serialize($mapmarkers, 'json');

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

        if ($book->getUser() == $this->getUser()) {
            $session->set('book', $book_id);
            $form = $this->createForm(ImageType::class, $file, array());
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
            'locale' => $locale,
            'book_id' => $book_id,
            'item' => $item,
            'title' => $book->getTitle(),
            'tabs' => $tabs,
            // gallery
            'pictures' => $pictures,
            'form' => $formview,
            //tags
            'repoTags' => $tooManyQueries,
            // talk
            'talks' => $talks,
            'talk_form' => $talk_formview,
            // map
            'thumbUrlDir' => $thumbUrlDir,
            'uploadDir' => $uploadDir,
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

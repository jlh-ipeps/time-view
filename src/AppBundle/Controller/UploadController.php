<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use AppBundle\Entity\Picture;
use AppBundle\Entity\File;
use AppBundle\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;

class UploadController extends Controller {
    
    public function processAction($id, Request $request) {

        $file = new File();
        $form = $this->createForm(ImageType::class, $file);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            throw new Exception('Country create: form is not submitted.');
        }
        
        $em = $this->getDoctrine()->getManager();
        $bookRepo = $em->getRepository('AppBundle:Book');
        $book = $bookRepo->find($id);
        
        $picture = new Picture();
        if ($form->isValid()) {
            
            $data = $form->getData();
//            dump($data->getFile());die();

            $upload = $this->get('appbundle.service.image_upload')->upload($data->getFile());

            if (is_array($upload)) {
                dump($upload);die();
            } else {
//                exit('Do something with failure.');
                dump('Do something with failure.');die();
            }

            $file->addPicture($picture);
            $picture->setBook($book);
            $picture->setFile($file);
            $em->persist($picture);
            $em->persist($file);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Upload done.');
            
            return $this->redirectToRoute('picture', array('id' => $picture->getFile()->getId()));
        }
    }
}

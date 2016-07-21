<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;

class RestController extends FOSRestController
{

    public function getAllMessagesAction()
    {
        $serializer = $this->get('jms_serializer');
        $response = new JsonResponse();

//        $messages = $this->getDoctrine()
//            ->getRepository('ApiBundle:Message')
//            ->getAllMessages();
        $messages = $this->get('doctrine_mongodb')
            ->getRepository('ApiBundle:Message')
            ->findAll();

        $messagesSerialized = $serializer->serialize($messages, 'json');

        $response->setContent($messagesSerialized);
        return $response;
    }

    public function getAllFormsAction()
    {
        $serializer = $this->get('jms_serializer');

        $response = new JsonResponse();
//        $forms = $this->getDoctrine()
//                      ->getRepository('ApiBundle:Message')
//                      ->getAllForms();
        $forms = $this->get('doctrine_mongodb')
            ->getRepository('ApiBundle:Message')
            ->getAllForms();

        $response->setContent($serializer->serialize($forms, 'json'));

        return $response;
    }

    public function getMessageByIdAction(Request $request, $id)
    {
        $serializer = $this->get('jms_serializer');

        $response = new JsonResponse();
        $message = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getMessageById($id);

        $response->setContent($serializer->serialize($message, 'json'));
        return $response;

    }

    public function getFormByMessageIdAction(Request $request, $id)
    {
        $serializer = $this->get('jms_serializer');

        $response = new JsonResponse();
        $form = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getFormByMessageId($id);

        $response->setContent($serializer->serialize($form, 'json'));


        return $response;
    }


    public function postNewMessageAction(Request $request)
    {

        return $this->processForm(new Message(), $request);
    }


    public function updateMessageAction(Message $message, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('ApiBundle\Form\MessageType', $message);

        $form->handleRequest($request);

        $response = new JsonResponse();

        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();

            $this->redirectToRoute('get_form_by__message_id', array('id' => $message->getId()));
            return $message;
        }
        $statusCode = $em->contains($message) ? 201 : 204;
        $response->setStatusCode($statusCode);

        return $response;

    }


    public function deleteMessageAction($id)
    {
        return $this->deleteForm($id);
    }


    private function processForm(Message $message, Request $request)
    {

        $em = $this->getDoctrine()->getManager();


        $form = $this->createForm('ApiBundle\Form\MessageType', $message);

        $form->handleRequest($request);

        $response = new JsonResponse();

        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();

            $this->redirectToRoute('get_form_by__message_id', array('id' => $message->getId()));
            return $message;
        }

        $statusCode = $em->contains($message) ? 201 : 204;
        $response->setStatusCode($statusCode);


        return $response;
    }

    private function deleteForm($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $this->getDoctrine()->getRepository('ApiBundle:Message')->find($id);

        $response = new JsonResponse();
        if (!$message) {
            $response->setStatusCode(204);
            throw $this->createNotFoundException('No message found for id ' . $id);
        }

        $em->remove($message);
        $em->flush();

        $response->setStatusCode(200);
        return $response;
    }

//    private function getHalValues($uri, $id)
//    {
//        $dirName = dirname($uri);
//
//        $halLinks = array(
//            'self' => array('href' => "$uri"),
//        );
//
//        if ($this->getDoctrine()
//            ->getRepository('ApiBundle:Message')->exists($id + 1)
//        ) {
//            $halLinks['next'] = array('href' => "$dirName/" . ($id + 1));
//        }
//        if (($id - 1) !== 0) {
//            $halLinks['prev'] = array('href' => "$dirName/" . ($id - 1));
//        }
//        return $halLinks;
//    }


}

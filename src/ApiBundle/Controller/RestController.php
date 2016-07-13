<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class RestController extends Controller
{
    /**
     * @Route("/api/messages", name="get_all_messages")
     * @Method({"GET","HEAD"})
     */
    public function getAllMessages()
    {
        $response = new JsonResponse();
        $messages = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getAllMessages();


        return $response->setContent($messages);
    }

    /**
     * @Route("/api/messages/{id}", name="get_message_by_id")
     * @Method("GET")
     */
    public function getMessageById(Request $request, $id)
    {
        $response = new JsonResponse();
        $message = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getMessageById($request, $id);
        return $response->setContent($message);

    }

    /**
     * @Route("/api/form/{id}", name="get_form_by_id")
     * @Method({"GET","HEAD"})
     */
    public function getFormByMessageId(Request $request, $id)
    {
        $response = new JsonResponse();
        $form = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getFormByMessageId($request, $id);
        return $response->setContent($form);
    }

    /**
     * @Route("/api/messages", name="post_new_message")
     * @Method("POST")
     */
    public function postNewMessage(Request $request)
    {

        return $this->processForm(new Message(), $request);
    }

    /**
     * @Route("/api/messages/{id}", name="update_message")
     * @Method("PUT")
     */
    public function updateMessage(Message $message, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('ApiBundle\Form\MessageOnlyType', $message);

        $form->handleRequest($request);

        $response = new JsonResponse();

        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();

            $this->redirectToRoute('get_form_by_id', array('id' => $message->getId()));
            return $message;
        }
        $statusCode = $em->contains($message) ? 201 : 204;
        $response->setStatusCode($statusCode);

        return $response;

    }

    /**
     * @Route("/api/messages/{id}", name="delete_message")
     * @Method("DELETE")
     */
    public function deleteMessage($id)
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

            $this->redirectToRoute('get_form_by_id', array('id' => $message->getId()));
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


}

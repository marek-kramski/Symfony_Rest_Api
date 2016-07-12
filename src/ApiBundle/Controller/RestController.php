<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RestController extends Controller
{
    /**
     * @Route("/api/messages", name="get_all_messages")
     * @Method({"GET","HEAD"})
     */
    public function getAllMessages()
    {
        $response = new Response();

        $messages = $this->getDoctrine()->getRepository('ApiBundle:Message')->findAll();
        $messageValues = array();
        foreach ($messages as $message) {
            $messageValues[] = array(
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'added' => $message->getAdded(),
            );
        }
        $response->headers->set('Content-Type', 'application/json');

        return $response->setContent(json_encode(array('message' => $messageValues)));
    }

    /**
     * @Route("/api/messages/{id}", name="get_message_by_id")
     * @Method("GET")
     */
    public function getMessageById(Request $request, $id)
    {
        $message = $this->getDoctrine()->getRepository('ApiBundle:Message')->getMessageById($request, $id);
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        return $response->setContent($message);

    }

    /**
     * @Route("/api/form/{id}", name="get_form_by_id")
     * @Method({"GET","HEAD"})
     */
    public function getFormByMessageId(Request $request, $id)
    {
        $response = new Response();

        $message = $this->getDoctrine()->getRepository('ApiBundle:Message')->getById($id);
        $messageObject = $this->getDoctrine()->getRepository('ApiBundle:Message')->find($id);
        $email = $messageObject->getEmail();
        $contactInfo = $this->getDoctrine()->getRepository('ApiBundle:ContactInfo')->getInfoByEmail($email);

        $uri = $request->getUri();
        $dirName = dirname($uri);
        $currentResourceNext = intval(basename($uri));
        $currentResourcePrev = intval(basename($uri));

        $hal_links = array(
            'self' => array('href' => "$uri"),
        );

        if ($this->getDoctrine()->getRepository('ApiBundle:Message')->getById($currentResourceNext + 1)) {
            $hal_links['next'] = array('href' => "$dirName/" . ($currentResourceNext + 1));
        }
        if (($currentResourcePrev - 1) !== 0) {
            $hal_links['prev'] = array('href' => "$dirName/" . ($currentResourcePrev - 1));
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response->setContent(json_encode(array('_links' => $hal_links, 'Message' => $message, 'Contact_info' => $contactInfo), JSON_UNESCAPED_SLASHES));
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

        $response = new Response();
        $statusCode = $em->contains($message) ? 201 : 204;
        $response->setStatusCode($statusCode);

        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();

            $this->redirectToRoute('get_form_by_id', array('id' => $message->getId()));
            return $message;
        }
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

        $response = new Response();
        $statusCode = $em->contains($message) ? 201 : 204;
        $response->setStatusCode($statusCode);

        if ($form->isValid()) {
            $em->persist($message);
            $em->flush();

            $this->redirectToRoute('get_form_by_id', array('id' => $message->getId()));
            return $message;
        }

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function deleteForm($id)
    {
        $em = $this->getDoctrine()->getManager();
        $message = $this->getDoctrine()->getRepository('ApiBundle:Message')->find($id);

        if (!$message) {
            throw $this->createNotFoundException('No message found for id ' . $id);
        }

        $em->remove($message);
        $em->flush();
    }


}

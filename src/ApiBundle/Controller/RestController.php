<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class RestController extends Controller
{

    public function getAllMessagesAction()
    {
        $response = new JsonResponse();
        $messages = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getAllMessages();

        $response->setContent(json_encode($messages));
        return $response;
    }

    public function getAllFormsAction()
    {
        $response = new JsonResponse();
        $forms = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getAllForms();

        $response->setContent(json_encode($forms));

        return $response;
    }

    public function getMessageByIdAction(Request $request, $id)
    {
        $response = new JsonResponse();
        $uri = $request->getUri();
        $message = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getMessageById($id);
        $halLinks = $this->getHalValues($uri, $id);

        $messageWithHal = array('_links' => $halLinks, 'message' => $message);

        $response->setContent(json_encode($messageWithHal));
        return $response;

    }

    public function getFormByMessageIdAction(Request $request, $id)
    {
        $response = new JsonResponse();
        $uri = $request->getUri();
        $form = $this->getDoctrine()
            ->getRepository('ApiBundle:Message')
            ->getFormByMessageId($id);

        $halLinks = $this->getHalValues($uri, $id);
        $formWithHal = json_encode(array('_links' => $halLinks, 'form' => $form));

        return $response->setContent($formWithHal);
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

            $this->redirectToRoute('get_form_by_id', array('id' => $message->getId()));
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

    private function getHalValues($uri, $id)
    {
        $dirName = dirname($uri);

        $halLinks = array(
            'self' => array('href' => "$uri"),
        );

        if ($this->getDoctrine()
            ->getRepository('ApiBundle:Message')->exists($id + 1)
        ) {
            $halLinks['next'] = array('href' => "$dirName/" . ($id + 1));
        }
        if (($id - 1) !== 0) {
            $halLinks['prev'] = array('href' => "$dirName/" . ($id - 1));
        }
        return $halLinks;
    }


}

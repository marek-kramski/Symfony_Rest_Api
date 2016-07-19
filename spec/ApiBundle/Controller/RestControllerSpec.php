<?php

namespace spec\ApiBundle\Controller;

use ApiBundle\Entity\Message;
use ApiBundle\Repository\MessageRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Routing\Router;

class RestControllerSpec extends ObjectBehavior
{
    function let(Container $container, Registry $doctrine, MessageRepository $repository, Request $request, FormFactory $formFactory, FormBuilder $formBuilder, Form $form, FormView $formView, Router $router)
    {
        $container->get('doctrine')->willReturn($doctrine);
        $container->get('form.factory')->willReturn($formFactory);
        $container->get('request')->willReturn($request);

        $formFactory->createBuilder(Argument::cetera())->willReturn($formBuilder);
        $formBuilder->getForm(Argument::cetera())->willReturn($form);
        $formFactory->create(Argument::cetera())->willReturn($form);
        $form->createView()->willReturn($formView);
//        $doctrine->getManager()->willReturn($entityManager);
        $doctrine->getRepository(Argument::any())->willReturn($repository);
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ApiBundle\Controller\RestController');
    }

    function it_should_return_all_messages_in_json($repository, $doctrine)
    {
        $jsonResponse = new JsonResponse();
        $doctrine->getRepository(Argument::exact('ApiBundle:Messages'))->willReturn($repository);
        $repository->getAllMessages()->willReturn('messages');
        $this->getAllMessages()->shouldBeLike($jsonResponse->setContent(json_encode('messages')));
        $this->getAllMessages()->shouldHaveType('Symfony\Component\HttpFoundation\JsonResponse');
    }

    function it_should_return_message_by_id_in_json($repository, $doctrine, $request)
    {
        $jsonResponse = new JsonResponse();
        $id = 1;
        $doctrine->getRepository(Argument::exact('ApiBundle:Messages'))->willReturn($repository);
        $repository->getMessageById($id)->willReturn('message');
        $repository->exists($id + 1)->willReturn(true);

//        $this->getMessageById($request, 1)->shouldBeLike($jsonResponse->setContent(json_encode(array('_links', 'message'))));
        $this->getMessageById($request, 1)->shouldHaveType('Symfony\Component\HttpFoundation\JsonResponse');
    }

    function it_should_create_an_object_when_form_is_valid($request, $form, $formFactory, $doctrine, EntityManager $entityManager)
    {
        $message = new Message();

        $formFactory->create('ApiBundle\Form\MessageType', $message);
        $form->handleRequest($request)->willReturn($form);
        $form->isValid()->willReturn(true);

        
        $entityManager->persist($message)->shouldBeCalled();
        $entityManager->flush()->shouldBeCalled();
    }
}
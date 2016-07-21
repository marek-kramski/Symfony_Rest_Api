<?php

namespace ApiBundle\DataFixtures\MongoDB;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use ApiBundle\Document\Message;
use ApiBundle\Document\ContactInfo;

class LoadMessageWithContact implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $message = new Message();
        $contactInfo = new ContactInfo();

        $message->setContent('ble ble ble');
        $message->setAdded(new \DateTime());
        $message->setContactInfoId();

        $contactInfo->setEmail('mail2@mail2.com');
        $contactInfo->setName('Tom21');
        $contactInfo->setPhone('124123123');

        $manager->persist($contactInfo);
        $manager->persist($message);

        $manager->flush();
    }

}
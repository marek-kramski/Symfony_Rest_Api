<?php

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Hateoas\Relation("self", href= "expr('/api/messages/' ~ object.getId())")
 * @Hateoas\Relation(
 *     "next",
 *     href= @Hateoas\Route("get_message_by_id", parameters = {"id" = "expr(object.getId() + 1)"})
 * )
 * @Hateoas\Relation(
 *     "prev",
 *     href= @Hateoas\Route("get_message_by_id", parameters = {"id" = "expr(object.getId() - 1)"})
 * )
 * @Document
 */
class Message
{
    /**
     * @ODM\Field(strategy="AUTO",type="integer")
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     */
    private $content;


    /**
     * @ODM\ReferenceOne(targetDocument="ContactInfo", inversedBy="messages")
     */
    protected $contactInfoId;

    /**
     * @ODM\Field(type="timestamp")
     */
    private $added;

    public function __construct()
    {
        $this->setAdded(new \DateTime());
    }
}

/**
 * ContactInfo
 *
 * @Document
 */
class ContactInfo
{
    /**
     * @ODM\Id
     * @ODM\Field(strategy="AUTO",type="integer")
     */
    private $id;

    /**
     * @ODM\Field(type="string")
     */
    private $name;

    /**
     * @ODM\Field(type="string")
     */
    private $email;

    /**
     * @ODM\Field(type="string")
     */
    private $phone;

    /**
     * @ReferenceMany(targetDocument="Message", mappedBy="contactInfoId")
     */
    protected $messages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }
}
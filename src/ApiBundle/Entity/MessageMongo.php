<?php

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

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
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="messages", cascade={"persist"})
     * @ORM\JoinColumn(name="contactInfoId", referencedColumnName="id")
     */
    protected $contactInfoId;

    /**
     * @ODM\Field(type="timestamp")
     */
    private $added;

    /**
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        $this->added = date_create(date('Y-m-d H:i:s'));
    }

    public function __construct()
    {
        $this->setAdded(new \DateTime());
    }
}

/**
 * ContactInfo
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\ContactInfoRepository")
 * @ORM\Table(name="contact_info")
 * @ORM\HasLifecycleCallbacks
 */
class ContactInfo
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="message", mappedBy="contactInfoId", cascade={"persist"})
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
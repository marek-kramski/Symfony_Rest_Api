<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;


/**
 * Message
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\MessageRepository")
 * @ORM\Table(name="message")
 * @Hateoas\Relation(
 *     "self"
 *     href=@Hateoas\Route(
 *
 */
class Message
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=5000)
     */
    private $content;


    /**
     * @ORM\ManyToOne(targetEntity="ContactInfo", inversedBy="messages", cascade={"persist"})
     * @ORM\JoinColumn(name="contactInfoId", referencedColumnName="id")
     */
    protected $contactInfoId;

    /**
     * @ORM\Column(type="datetime")
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

    public function getContactInfo()
    {
        return $this->contactInfoId;
    }

    public function setContactInfo(ContactInfo $email = null)
    {
        $this->contactInfoId = $email;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set added
     *
     * @param \DateTime $added
     *
     * @return Message
     */
    public function setAdded($added)
    {
        $this->added = $added;

        return $this;
    }

    /**
     * Get added
     *
     * @return \DateTime
     */
    public function getAdded()
    {
        return $this->added;
    }

    /**
     * Set contactInfoId
     *
     * @param \ApiBundle\Entity\ContactInfo $contactInfoId
     *
     * @return Message
     */
    public function setContactInfoId(\ApiBundle\Entity\ContactInfo $contactInfoId = null)
    {
        $this->contactInfoId = $contactInfoId;

        return $this;
    }

    /**
     * Get contactInfoId
     *
     * @return \ApiBundle\Entity\ContactInfo
     */
    public function getContactInfoId()
    {
        return $this->contactInfoId;
    }
}

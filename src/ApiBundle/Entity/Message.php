<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Message
 *
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\MessageRepository")
 * @ORM\Table(name="message")
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
     * @ORM\JoinColumn(name="email", referencedColumnName="email")
     */
    protected $email;

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
     * Set email
     *
     * @param \ApiBundle\Entity\ContactInfo $email
     *
     * @return Message
     */
    public function setEmail(ContactInfo $email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return \ApiBundle\Entity\ContactInfo
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function getContactInfo()
    {
        return $this->email;
    }

    public function setContactInfo(ContactInfo $email = null)
    {
        $this->email = $email;

        return $this;
    }
}

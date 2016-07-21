<?php

namespace ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ODM\Document(repositoryClass="ApiBundle\DocumentRepository\MessageRepository")
 * @Hateoas\Relation(
 *     "self",
 *     href= @Hateoas\Route("get_message_by_id", parameters = {"id" = "expr(object.getId())"})
 * )
 * @Hateoas\Relation(
 *     "next",
 *     href= @Hateoas\Route("get_message_by_id", parameters = {"id" = "expr(object.getId() + 1)"}),
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr(object.nextExists() !== null)")
 * )
 * @Hateoas\Relation(
 *     "prev",
 *     href= @Hateoas\Route("get_message_by_id", parameters = {"id" = "expr(object.getId() - 1)"}),
 *     exclusion = @Hateoas\Exclusion(excludeIf = "expr((object.getId() - 1) < 1)")
 * )
 *
 */
class Message
{
    /**
     * @ODM\Id(strategy="INCREMENT")
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
     * @ODM\Field(type="date")
     */
    protected $added;

    public function __construct()
    {
//        $this->setAdded(new \DateTime());
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
     * @param \ApiBundle\Document\ContactInfo $contactInfoId
     *
     * @return Message
     */
    public function setContactInfoId(ContactInfo $contactInfoId = null)
    {
        $this->contactInfoId = $contactInfoId;

        return $this;
    }

    /**
     * Get contactInfoId
     *
     * @return \ApiBundle\Document\ContactInfo
     */
    public function getContactInfoId()
    {
        return $this->contactInfoId;
    }

    public function nextExists()
    {
    }


}

<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends EntityRepository
{
    public function getAllMessages()
    {
        $messages = $this->findAll();
        $messageValues = array();
        foreach ($messages as $message) {
            $messageValues[] = array(
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'added' => $message->getAdded(),
            );
        }
        return array('messages' => $messageValues);
    }

    public function getMessageById($id)
    {
        $message = $this->find($id);

        $messageValues['id'] = $message->getId();
        $messageValues['content'] = $message->getContent();
        $messageValues['added'] = $message->getAdded();


        return array('message' => $messageValues,);
    }

    public function getFormByMessageId($id)
    {
        $message = $this->getById($id);
        $messageObject = $this->find($id);
        $email = $messageObject->getEmail();
        $contactInfo = $this->getEntityManager()->getRepository('ApiBundle:ContactInfo')->getInfoByEmail($email);

        return array('message' => $message, 'Contact_info' => $contactInfo);

    }

    public function getById($id)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery('
        SELECT m
        FROM ApiBundle:Message m
        WHERE m.id = :id
        ')->setParameter('id', $id);
//        $query->setHint(Query::HINT_INCLUDE_META_COLUMNS, true);

        return $query->getResult(Query::HYDRATE_ARRAY);
    }

    public function exists($id)
    {
        $exists = $this->getById($id);
        var_dump($exists);
        return null !== $this->find($id);
    }
    


}

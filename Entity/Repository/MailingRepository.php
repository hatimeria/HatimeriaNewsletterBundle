<?php

namespace Hatimeria\NewsletterBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MailingRepository extends EntityRepository
{
    /**
     * Finds mailing objects which wasn't sent already
     * 
     * @return array
     */
    public function findMailingToSend()
    {
        return $this->findBy(array('sent' => false));
    }

}
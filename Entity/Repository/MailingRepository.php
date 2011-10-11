<?php

namespace Hatimeria\NewsletterBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class MailingRepository extends EntityRepository
{
    public function findMailingToSend()
    {
        return $this->findBy(array('sent' => false));
    }

}
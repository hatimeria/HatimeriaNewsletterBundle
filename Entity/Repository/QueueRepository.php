<?php

namespace Hatimeria\NewsletterBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class QueueRepository extends EntityRepository
{
    public function findPacket($limit = null)
    {
        $query = $this->createQueryBuilder('e');

        if (null !== $limit) {
            $query->setMaxResults((int) $limit);
        }

        return $query->orderBy('e.createdAt')->getQuery()->getResult();
    }
    
}
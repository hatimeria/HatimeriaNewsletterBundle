<?php

namespace Hatimeria\NewsletterBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class QueueRepository extends EntityRepository
{
    /**
     * Select part of records to send from queue table
     *
     * @param int $limit
     * @return array
     */
    public function findPacket($limit = null)
    {
        $query = $this->createQueryBuilder('e');

        if (null !== $limit) {
            $query->setMaxResults((int) $limit);
        }

        return $query->orderBy('e.createdAt')->getQuery()->getResult();
    }
    
}
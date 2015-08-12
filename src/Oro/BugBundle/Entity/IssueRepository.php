<?php

namespace Oro\BugBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Oro\Bundle\UserBundle\Entity\User;

/**
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * Class IssueRepository
 * @package BugBundle\Entity
 */
class IssueRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function getIssuesByStatus()
    {
        return $this->createQueryBuilder('i')
            ->select('COUNT(i.id) as issues, type.name as typeOfIssue')
            ->innerJoin('i.type', 'type')
            ->groupBy('type.id')
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace Oro\BugBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * Class IssueRepository
 * @package BugBundle\Entity
 */
class IssueRepository extends EntityRepository
{
    public function getAllIssuesQuery()
    {
        return $this->getEntityManager()->createQuery("SELECT i FROM BugBundle:issue i");
    }

    public function getIssuesByUserQuery(User $user)
    {
        return $this->getIssuesByUserQueryBuilder($user)->getQuery();
    }

    public function getIssuesByUserQueryBuilder(User $user, $count = false)
    {
        $qb = $this->createQueryBuilder('i');
        if ($count) {
            $qb->select('count(i.id)');
        }
        $qb->innerJoin('i.project', 'p')
            ->leftJoin('p.members', 'members');
        $qb->where(
            $qb->expr()->in('members', ':user')
        )
            ->setParameter('user', $user);

        return $qb;
    }

    /**
     * @param User $user
     * @param Issue $issue
     * @return mixed
     */
    public function checkIssueUserAccess(User $user, Issue $issue)
    {
        $result = $this->getIssuesByUserQueryBuilder($user, true)
            ->andWhere('i = :issue')
            ->setParameter('issue', $issue)
            ->getQuery()->getSingleScalarResult();

        return $result;
    }

    public function getActualIssuesByUserCollaboratorQuery(User $user)
    {
        $qb = $this->createQueryBuilder('i')
            ->innerJoin('i.collaborators', 'collaborators')
            ->innerJoin('i.status', 'status');
        $qb->where(
            $qb->expr()->in('collaborators', ':user')
        )
            ->andWhere('status.open = true')
            ->setParameter('user', $user);


        return $qb->getQuery();
    }

    public function getActualIssuesByUserCollaborator(User $user)
    {
        return $this->getActualIssuesByUserCollaboratorQuery($user)->getResult();
    }
}

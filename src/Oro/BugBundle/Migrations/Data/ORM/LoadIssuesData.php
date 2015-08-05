<?php

namespace Oro\BugBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\BugBundle\Entity\Issue;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\OrganizationBundle\Migrations\Data\ORM\LoadOrganizationAndBusinessUnitData;
use Oro\Bundle\UserBundle\Entity\User;

class LoadIssuesData extends AbstractFixture implements DependentFixtureInterface
{

    /** @var  ObjectManager */
    private $manager;

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [
            'Oro\BugBundle\Migrations\Data\ORM\LoadDictionariesData',
            'Oro\BugBundle\Migrations\Data\ORM\LoadUsersData',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {

        $this->manager = $manager;
        $className = ExtendHelper::buildEnumValueClassName('oro_issue_type');
        $typeSubtask = $manager->getRepository($className)->find(Issue::TYPE_SUBTASK);
        $user = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => LoadUsersData::USER]);
        $manager1 = $manager->getRepository('OroUserBundle:User')->findOneBy(['username' => LoadUsersData::MANAGER]);
        $userIssue = $this->createIssue($user);
        $managerIssue = $this->createIssue($manager1, $typeSubtask, $userIssue);
        $managerIssue->addCollaborator($user);
        $managerIssue->addCollaborator($manager1);
        $this->manager->persist($userIssue);
        $this->manager->persist($managerIssue);
        $this->manager->flush();
    }

    /**
     * @param User $user
     * @param null $type
     * @param Issue|null $parentIssue
     * @return Issue
     */
    private function createIssue(User $user, $type = null, Issue $parentIssue = null)
    {
        $issue = new Issue();
        $repo = $this->manager->getRepository('OroOrganizationBundle:Organization');
        /** @var Organization $organization */
        $organization = $repo->findOneBy(['name' => LoadOrganizationAndBusinessUnitData::MAIN_ORGANIZATION]);
        $issue->setOrganization($organization);
        $className = ExtendHelper::buildEnumValueClassName('oro_issue_type');
        $issue->setAssignee($user);
        $issue->setOwner($user);
        $issue->setDescription('It is test description for task created by '.$user);
        $issue->setSummary('Summary for task created by '.$user);
        if ($parentIssue) {
            $issue->setParentIssue($parentIssue);
        }
        if (!$type) {
            $issue->setType($this->manager->getRepository($className)->find(Issue::TYPE_TASK));
        } else {
            $issue->setType($type);
        }
        $issue->setResolution($this->manager->getRepository('OroBugBundle:IssueResolution')->findOneBy([]));
        $issue->setPriority($this->manager->getRepository('OroBugBundle:IssuePriority')->findOneBy([]));
        $issue->setStatus($this->manager->getRepository('OroBugBundle:IssueStatus')->findOneBy([]));

        return $issue;
    }
}

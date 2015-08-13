<?php

namespace Oro\BugBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Oro\BugBundle\Entity\Issue;
use Oro\BugBundle\Entity\IssuePriority;
use Oro\BugBundle\Entity\IssueResolution;
use Oro\BugBundle\Entity\IssueStatus;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\OrganizationBundle\Entity\Organization;
use Oro\Bundle\UserBundle\Entity\User;

class IssueTest extends AbstractEntityTestCase
{
    /** @var Issue */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Oro\BugBundle\Entity\Issue';
    }

    /**
     * {@inheritDoc}
     */
    public function getSetDataProvider()
    {

        $summary = 'summary';
        $code = 'code';
        $description = 'description';

        $priority = new IssuePriority();
        $status = new IssueStatus();
        $issueResolution = new IssueResolution();
        $reporter = new User();
        $assignee = new User();
        $collaborators = new ArrayCollection([new User(), new User()]);
        $parentIssue = new Issue();
        $childrenIssues = new ArrayCollection([new Issue(), new Issue()]);
        $created = new \DateTime('now');
        $updated = new \DateTime('now');
        $org = new Organization();

        return [
            'summary' => ['summary', $summary, $summary],
            'code' => ['code', $code, $code],
            'description' => ['description', $description, $description],
            'priority' => ['priority', $priority, $priority],
            'status' => ['status', $status, $status],
            'resolution' => ['resolution', $issueResolution, $issueResolution],
            'owner' => ['owner', $reporter, $reporter],
            'assignee' => ['assignee', $assignee, $assignee],
            'collaborators' => ['collaborators', $collaborators, $collaborators],
            'parentIssue' => ['parentIssue', $parentIssue, $parentIssue],
            'childrenIssues' => ['childrenIssues', $childrenIssues, $childrenIssues],
            'created' => ['created', $created, $created],
            'updated' => ['updated', $updated, $updated],
            'organization' => ['organization', $org, $org],
        ];
    }

    public function testMethods()
    {
        $issue = new Issue();
        $childrenIssue = new Issue();
        $issue->setCode('code');
        $issue->setSummary('summary');
        $user = (new User())->setEmail('test@mail.com');
        $issue->setOwner($user);
        $issue->addCollaborator($user);
        $issue->addChildrenIssue($childrenIssue);
        $this->assertEquals($issue->__toString(), 'code summary');
        $this->assertEquals($issue->getEmail(), 'test@mail.com');
        $this->assertContains($user, $issue->getCollaborators());
        $issue->removeCollaborator($user);
        $this->assertNotContains($user, $issue->getCollaborators());
        $this->assertContains($childrenIssue, $issue->getChildrenIssues());
        $issue->removeChildrenIssue($childrenIssue);
        $this->assertNotContains($childrenIssue, $issue->getChildrenIssues());
        $this->assertNull($issue->getId());

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $issue->setCreatedNow();
        $issue->setUpdatedNow();
        $this->assertEquals($now, $issue->getCreated());
        $this->assertEquals($now, $issue->getUpdated());

        $issue->setCodeStamp();
        $this->assertNotNull($issue->getCode());

        $blankIssue = new Issue();
        $this->setExpectedException('Doctrine\ORM\EntityNotFoundException');
        $blankIssue->getEmail();
    }


}

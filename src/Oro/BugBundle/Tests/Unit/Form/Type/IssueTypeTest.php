<?php
namespace Oro\BugBundle\Tests\Unit\Form\Type;

use Oro\BugBundle\Entity\Issue;
use Oro\BugBundle\Entity\IssuePriority;
use Oro\BugBundle\Entity\IssueResolution;
use Oro\BugBundle\Entity\IssueStatus;
use Oro\BugBundle\Form\Type\ChildrenIssueType;
use Oro\BugBundle\Form\Type\IssueType;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\PreloadedExtension;

class IssueTypeTest extends AbstractFormTestCase
{


    /** @var  IssuePriority[] */
    private $priorities;

    /** @var  IssueStatus[] */
    private $statuses;

    /** @var  IssueResolution[] */
    private $resolutions;

    /** @var  Issue[] */
    private $issues;


    /** @var  User[] */
    private $users;

    /** @var array */
    private $types;

    /**
     * @dataProvider formDataProvider
     * @param Issue $issue
     * @param $formData
     * @param AbstractType $issueType
     */
    public function testSubmitValidData(Issue $issue, $formData, AbstractType $issueType)
    {
        $form = $this->factory->create($issueType);
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($issue, $form->getData());
    }

    public function formDataProvider()
    {
        /** @var Issue $issue */
        $issue = $this->getEntity('Oro\BugBundle\Tests\Unit\Form\Type\Issue', ['code', 'summary', 'description'], true);
        /** @var User $user */
        //case 1
        $this->priorities = $this->getEntitySet('Oro\BugBundle\Entity\IssuePriority', ['label']);
        $issue->setPriority($this->priorities[1]);
        $this->statuses = $this->getEntitySet('Oro\BugBundle\Entity\IssueStatus', ['label']);
        $issue->setStatus($this->statuses[1]);
        $this->resolutions = $this->getEntitySet('Oro\BugBundle\Entity\IssueResolution', ['label']);
        $issue->setResolution($this->resolutions[1]);
        $type = new IssueTypeStub();
        $type->setId('story');
        $this->types = [1 => $type];
        $issue->setType($this->types[1]);
        $this->users = $this->getEntitySet('Oro\Bundle\UserBundle\Entity\User', ['username'], 10);
        $user = $this->users[1];
        $issue->setAssignee($this->users[2]);
        $issue->setOwner($user);

        //case 2
        $issue2 = clone $issue;
        $this->issues = $this->getEntitySet('Oro\BugBundle\Tests\Unit\Form\Type\Issue', ['code']);
        $parentIssue = $this->issues[1];
        $issue2->setParentIssue($parentIssue);

        $formData1 = [
            'code' => 'code',
            'summary' => 'summary',
            'description' => 'description',
            'priority' => '1',
            'resolution' => '1',
            'type' => '1',
            'assignee' => '2',
            'owner' => '1',
        ];

        $formData2 = $formData1;
        $formData2['parentIssue'] = 1;


        $dataProvider = [
            [$issue, $formData1, new IssueType()],
            [$issue2, $formData2, new ChildrenIssueType()],
        ];

        return $dataProvider;
    }


    /**
     * @return array
     */
    protected function getExtensions()
    {
        $this->formDataProvider();
        $paramsSet = [
            [$this->types, 'bug_select_issue_type'],
            [$this->priorities, 'bug_select_issue_priority'],
            [$this->statuses, 'bug_select_issue_status'],
            [$this->resolutions, 'bug_select_issue_resolution'],
            [$this->users, 'bug_select_user'],
            [$this->issues, 'oro_user_select'],
        ];
        $stubs = $this->getEntityStubs($paramsSet);

        return [new PreloadedExtension($stubs, [])];
    }
}

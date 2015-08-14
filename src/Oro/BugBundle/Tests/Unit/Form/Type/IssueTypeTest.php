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

    public function setUp()
    {
        self::markTestSkipped('Issue Type');
    }

    /**
     * @dataProvider formDataProvider
     * @param Issue $issue
     * @param $formData
     * @param AbstractType $issueType
     */
    public function testSubmitValidData(Issue $issue, $formData, AbstractType $issueType)
    {

        if ($issueType instanceof IssueType) {
            $form = $this->factory->create($issueType);
        } else {
            $form = $this->factory->create($issueType, null, ['parentIssue' => $this->issues[1]]);
        }
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();
        $this->assertEquals($issue, $data);
    }

    public function formDataProvider()
    {
        /** @var Issue $issue */
        $issue = $this->getEntity('Oro\BugBundle\Tests\Unit\Form\Type\Issue', ['summary', 'description'], true);
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
        $issue->setAssignee($this->users[2]);


        //case 2
        $issue2 = clone $issue;
        $this->issues = $this->getEntitySet('Oro\BugBundle\Entity\Issue', ['code']);
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
            'status' => '1',
        ];

        $formData2 = $formData1;
        $formData2['parentIssue'] = 1;

        $issueType = new IssueType();
        $issueType->setDataClass('Oro\BugBundle\Tests\Unit\Form\Type\Issue');
        $childrenIssueType = new ChildrenIssueType();
        $issueType->setDataClass('Oro\BugBundle\Tests\Unit\Form\Type\Issue');

        $dataProvider = [
            [$issue, $formData1, $issueType],
            [$issue2, $formData2, $childrenIssueType],
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
            [$this->users, 'oro_user_select'],
            [$this->issues, 'bug_children_issue'],
            [$this->issues, 'bug_issue'],
            [$this->issues, 'bug_parent_issue_select'],
        ];
        $stubs = $this->getEntityStubs($paramsSet);

        return [new PreloadedExtension($stubs, [])];
    }
}

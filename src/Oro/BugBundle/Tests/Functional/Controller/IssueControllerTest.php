<?php

namespace Oro\BugBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;
use Oro\BugBundle\Tests\TestHelper;

/**
 * @dbIsolation
 */
class IssueControllerTest extends WebTestCase
{
    use TestHelper;
    /** @var string */
    protected $summary = 'Test Case Summary';
    /** @var  User */
    protected $user;

    protected function setUp()
    {
        $this->initClient(['environment' => 'test'], $this->generateBasicAuthHeader());

    }

    public function testIndex()
    {
        $this->client->followRedirects(true);
        $this->client->request('GET', $this->getUrl('bug.issue_index'));
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
    }


    public function testCreate()
    {
        $this->client->followRedirects(true);
        $crawler = $this->client->request('GET', $this->getUrl('bug.issue_create'));
        $this->makeIssue($crawler);
    }


    /**
     * depends testCreate
     */
    public function testUpdate()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $issueId = $em->getRepository('OroBugBundle:Issue')->findOneBy(['parentIssue' => null])->getId();
        $crawler = $this->client->request('GET', $this->getUrl('bug.issue_update', ['id' => $issueId]));
        $this->makeIssue($crawler);
    }

    /**
     * depends testCreate
     */
    public function testCreateChildren()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $issueId = $em->getRepository('OroBugBundle:Issue')
            ->createQueryBuilder('i')
            ->where('i.parentIssue is not null')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
            ->getId();
        $crawler = $this->client->request('GET', $this->getUrl('bug.issue_children_create', ['id' => $issueId]));
        $form = $crawler->selectButton('Save and Close')->form();
        $this->setFormData($form, 'bug_children_issue');
        $crawler = $this->client->submit($form);
        $this->checkValidation($crawler, ['bug_children_issue_description', 'bug_children_issue_assignee']);

        $form['bug_children_issue[description]'] = 'Desc';
        $form['bug_children_issue[assignee]'] = $this->user->getId();
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $html = $crawler->html();
        $this->assertNotContains('validation-error', $html);
        $this->assertContains('grid-issues-grid', $html);
        $this->assertContains($this->summary, $html);
    }

    public function testDelete()
    {

    }

    /**
     * @param Crawler $crawler
     */
    private function makeIssue(Crawler $crawler)
    {
        /** @var Form $form */

        $form = $crawler->selectButton('Save and Close')->form();
        $form = $this->setFormData($form);
        //Validation test
        $this->client->followRedirects(true);
        $crawler = $this->client->submit($form);
        $this->checkValidation($crawler, ['bug_issue_description', 'bug_issue_assignee']);

        //Add missed fields
        $form['bug_issue[description]'] = 'Desc';
        $form['bug_issue[assignee]'] = $this->user->getId();
        $crawler = $this->client->submit($form);
        $result = $this->client->getResponse();

        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $html = $crawler->html();
        $this->assertNotContains('validation-error', $html);
        $this->assertContains('grid-issues-grid', $html);
        $this->assertContains($this->summary, $html);
    }

    /**
     * @param Form $form
     * @param string $alias
     * @return Form
     */
    private function setFormData(Form $form, $alias = 'bug_issue')
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $this->user = $user = $em->getRepository('OroUserBundle:User')->findOneBy([]);
        $priority = $em->getRepository('OroBugBundle:IssuePriority')->findOneBy([]);
        $resolution = $em->getRepository('OroBugBundle:IssueResolution')->findOneBy([]);
        $status = $em->getRepository('OroBugBundle:IssueStatus')->findOneBy([]);
        $summary = 'Test Case Summary';
        $form[$alias.'[owner]'] = $user->getId();
        $form[$alias.'[priority]'] = $priority->getId();
        $form[$alias.'[resolution]'] = $resolution->getId();
        if ($alias === 'bug_issue') {
            $form[$alias.'[type]'] = 2;
        }
        $form[$alias.'[summary]'] = $summary;
        $form[$alias.'[status]'] = $status->getId();
        $form[$alias.'[description]'] = '';
        $form[$alias.'[assignee]'] = null;

        return $form;
    }
}

<?php

namespace Oro\BugBundle\Tests\Functional\Entity;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class IssueRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    protected function setUp()
    {
        $this->initClient([], array_merge($this->generateBasicAuthHeader()));
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testGetIssuesByStatus()
    {
        $issues = $this->em
            ->getRepository('OroBugBundle:Issue')
            ->getIssuesByStatus();
        $this->assertNotCount(0, $issues);
    }
}
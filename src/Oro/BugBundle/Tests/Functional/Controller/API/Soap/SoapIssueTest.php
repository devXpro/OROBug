<?php

namespace BugBundle\Tests\Functional\Controller\API\Soap;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class SoapAccountTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());
        $this->initSoapClient();
    }

    /**
     * @return array
     */
    public function testCreate()
    {
        $request = [
            "issue" => 'Issue_name_'.mt_rand(),
            "owner" => '1',
        ];

        $result = $this->soapClient->createIssue($request);
        $this->assertTrue((bool)$result, $this->soapClient->__getLastResponse());

        $request['id'] = $result;

        return $request;
    }

    /**
     * @param array $request
     * @depends testCreate
     * @return array
     */
    public function testGet(array $request)
    {
        $issues = $this->soapClient->getIssues(1, 1000);
        $issues = $this->valueToArray($issues);
        $issueName = $request['issue'];
        $issue = $issues['item'];
        if (isset($issue[0])) {
            $issue = array_filter(
                $issue,
                function ($a) use ($issueName) {
                    return $a['issue'] == $issueName;
                }
            );
            $issue = reset($issue);
        }

        $this->assertEquals($request['issue'], $issue['issue']);
        $this->assertEquals($request['id'], $issue['id']);
    }

    /**
     * @param array $request
     * @depends testCreate
     * @return array
     */
    public function testUpdate(array $request)
    {
        $issueUpdate = $request;
        unset($issueUpdate['id']);
        $issueUpdate['name'] .= '_Updated';

        $result = $this->soapClient->updateIssue($request['id'], $issueUpdate);
        $this->assertTrue($result);

        $account = $this->soapClient->getIssue($request['id']);
        $account = $this->valueToArray($account);

        $this->assertEquals($issueUpdate['name'], $account['name']);

        return $request;
    }

    /**
     * @param array $request
     * @depends testUpdate
     */
    public function testDelete(array $request)
    {
        $result = $this->soapClient->deleteIssue($request['id']);
        $this->assertTrue($result);

        $this->setExpectedException('\SoapFault', 'Record with ID "'.$request['id'].'" can not be found');
        $this->soapClient->getIssue($request['id']);
    }
}

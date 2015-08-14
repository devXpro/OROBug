<?php

namespace BugBundle\Tests\Functional\Controller\API\Rest;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class RestIssueTest extends WebTestCase
{
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());
        self::markTestSkipped('Rest');
    }

    /**
     * @return array
     */
    public function testCreate()
    {
        $request = [
            'issue' => [
                'name' => 'Issue_name_'.mt_rand(),
                'owner' => '1',
            ],
        ];

        $this->client->request(
            'POST',
            $this->getUrl('bug_api_post_issue'),
            $request
        );
        $result = $this->getJsonResponseContent($this->client->getResponse(), 201);
        $this->assertArrayHasKey('id', $result);
        $request['id'] = $result['id'];

        return $request;
    }

    /**
     * @param array $request
     * @depends testCreate
     * @return array
     */
    public function testGet(array $request)
    {
        $this->client->request(
            'GET',
            $this->getUrl('bug_api_get_issues')
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $id = $request['id'];
        $result = array_filter(
            $result,
            function ($a) use ($id) {
                return $a['id'] == $id;
            }
        );

        $this->assertNotEmpty($result);
        $this->assertEquals($request['issue']['summary'], reset($result)['summary']);

        $this->client->request(
            'GET',
            $this->getUrl('bug_api_get_issue', ['id' => $request['id']])
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals($request['issue']['summary'], $result['summary']);
    }

    /**
     * @param array $request
     * @depends testCreate
     * @depends testGet
     */
    public function testUpdate(array $request)
    {
        $request['issue']['summary'] .= '_Updated';
        $this->client->request(
            'PUT',
            $this->getUrl('bug_api_put_issue', ['id' => $request['id']]),
            $request
        );
        $result = $this->client->getResponse();

        $this->assertEmptyResponseStatusCodeEquals($result, 204);

        $this->client->request(
            'GET',
            $this->getUrl('bug_api_get_issue', ['id' => $request['id']])
        );

        $result = $this->getJsonResponseContent($this->client->getResponse(), 200);

        $this->assertEquals(
            $request['issue']['summary'],
            $result['summary']
        );
    }

    /**
     * @param array $request
     * @depends testCreate
     */
    public function testDelete(array $request)
    {
        $this->client->request(
            'DELETE',
            $this->getUrl('bug_api_delete_issue', ['id' => $request['id']])
        );
        $result = $this->client->getResponse();
        $this->assertEmptyResponseStatusCodeEquals($result, 204);
        $this->client->request('GET', $this->getUrl('bug_api_get_issue', ['id' => $request['id']]));
        $result = $this->client->getResponse();
        $this->assertJsonResponseStatusCodeEquals($result, 404);
    }
}

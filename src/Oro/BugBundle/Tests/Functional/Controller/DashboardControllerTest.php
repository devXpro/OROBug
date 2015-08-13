<?php
namespace Oro\BugBundle\Tests\Functional\Controller;

use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

class DashboardControllerTest extends WebTestCase
{

    protected function setUp()
    {
        $this->initClient([], $this->generateBasicAuthHeader());
    }

    public function testBuildChartWidgetAction()
    {
        $crawler = $this->client->request(
            'GET',
            $this->getUrl('oro_bug_chart_widget', ['widget' => 'oro_bug_chart'])
        );
        $result = $this->client->getResponse();
        $this->assertHtmlResponseStatusCodeEquals($result, 200);
        $this->assertContains('OroChartBundle:Chart:pie.html.twig', $crawler->html());
    }
}

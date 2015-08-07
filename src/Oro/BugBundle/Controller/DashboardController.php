<?php

namespace Oro\BugBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/issue/dashboard")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/issues/chart/{widget}", name="oro_bug_chart_widget", requirements={"widget"="[\w-]+"})
     * @Template("@OroBug/Dashboard/issueChartWidget.html.twig")
     * @param $widget
     * @return array
     */
    public function buildChartWidgetAction($widget)
    {
        $trans = $this->get('translator');
        $chartData = $this->getDoctrine()->getRepository('OroBugBundle:Issue')->getIssuesByStatus();
        foreach ($chartData as $key => $data) {
            $chartData[$key]['typeOfIssue'] = $trans->trans($data['typeOfIssue']);
        }
        $widgetAttr = $this->get('oro_dashboard.widget_configs')->getWidgetAttributesForTwig($widget);
        $widgetAttr['chartView'] = $this->get('oro_chart.view_builder')
            ->setArrayData($chartData)
            ->setOptions(
                [
                    'name' => 'pie_chart',
                    'data_schema' => [
                        'label' => ['field_name' => 'typeOfIssue'],
                        'value' => ['field_name' => 'issues'],
                    ],
                ]
            )
            ->getView();

        return $widgetAttr;
    }
}

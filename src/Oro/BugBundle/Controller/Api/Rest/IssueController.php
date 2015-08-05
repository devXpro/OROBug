<?php

namespace Oro\BugBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;

/**
 * @RouteResource("issue")
 * @NamePrefix("bug_api_")
 */
class IssueController extends RestController
{
    /**
     * @Acl(
     *      id="bug.issue_delete",
     *      type="entity",
     *      class="OroBugBundle:Issue",
     *      permission="DELETE"
     * )
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFormHandler()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getManager()
    {
        return $this->get('bug.issue_manager.api');
    }
}

<?php

namespace Oro\BugBundle\Controller\Api\Soap;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;

use Oro\Bundle\SoapBundle\Controller\Api\Soap\SoapController;

class IssueController extends SoapController
{
    /**
     * @Soap\Method("getIssues")
     * @Soap\Param("page", phpType="int")
     * @Soap\Param("limit", phpType="int")
     * @Soap\Result(phpType = "Oro\BugBundle\Entity\Issue[]")
     * @param int $page
     * @param int $limit
     * @return mixed|\Oro\Bundle\SoapBundle\Entity\SoapEntityInterface|\Traversable
     */
    public function cgetAction($page = 1, $limit = 10)
    {
        return $this->handleGetListRequest($page, $limit);
    }

    /**
     * @Soap\Method("getIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "Oro\BugBundle\Entity\Issue")
     * @param $id
     * @return mixed|object|\Oro\Bundle\SoapBundle\Entity\SoapEntityInterface
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * @Soap\Method("createIssue")
     * @Soap\Param("issue", phpType = "Oro\BugBundle\Entity\Issue")
     * @Soap\Result(phpType = "int")
     * @return int
     */
    public function createAction()
    {
        return $this->handleCreateRequest();
    }

    /**
     * @Soap\Method("updateIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Param("issue", phpType = "Oro\BugBundle\Entity\Issue")
     * @Soap\Result(phpType = "boolean")
     * @param $id
     * @param $issue
     * @return bool
     */
    public function updateAction($id, $issue)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * @Soap\Method("deleteIssue")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "boolean")
     * @param $id
     * @return bool
     * @throws \SoapFault
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
        return $this->container->get('bug.form.type.issue.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getFormHandler()
    {
        return $this->container->get('bug.form.handler.issue.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getManager()
    {
        return $this->container->get('bug.issue_manager.api');
    }
}

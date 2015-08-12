<?php

namespace Oro\BugBundle\Controller\Api\Rest;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @RouteResource("issue")
 * @NamePrefix("bug_api_")
 */
class IssueController extends RestController
{
    /**
     * REST DELETE
     *
     * @param $id
     * @ApiDoc(
     *      description="Delete Issue",
     *      resource=true
     * )
     * @Acl(
     *      id="bug.issue_delete",
     *      type="entity",
     *      class="OroBugBundle:Issue",
     *      permission="DELETE"
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * REST GET list
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of issues per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all issue items",
     *      resource=true
     * )
     * @AclAncestor("bug.issue_view")
     * @return Response
     */
    public function cgetAction()
    {
        $page = (int)$this->getRequest()->get('page', 1);
        $limit = (int)$this->getRequest()->get('limit', self::ITEMS_PER_PAGE);

        return $this->handleGetListRequest($page, $limit);
    }


    /**
     * REST GET item
     *
     * @param string $id
     *
     * @ApiDoc(
     *      description="Get issue item",
     *      resource=true
     * )
     * @AclAncestor("bug.issue_view")
     * @return Response
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }


    /**
     * REST PUT
     *
     * @param int $id Account item id
     *
     * @ApiDoc(
     *      description="Update issue",
     *      resource=true
     * )
     * @AclAncestor("bug.issue_update")
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }


    /**
     * Create new account
     *
     * @ApiDoc(
     *      description="Create new issue",
     *      resource=true
     * )
     * @AclAncestor("bug.issue_create")
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }


    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->get('bug.form.type.issue.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getFormHandler()
    {
        return $this->get('bug.form.handler.issue.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getManager()
    {
        return $this->get('bug.issue_manager.api');
    }
}

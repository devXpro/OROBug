<?php

namespace Oro\BugBundle\Controller;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IssueController extends Controller
{
    /**
     * @Route("/issue/index", name="bug.issue_index")
     * @Template
     * @Acl(
     *     id="bug.issue.issue_view",
     *     type="entity",
     *     class="OroBugBundle:Issue",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return ['entity_class' => 'Oro\BugBundle\Entity\Issue'];
    }
}

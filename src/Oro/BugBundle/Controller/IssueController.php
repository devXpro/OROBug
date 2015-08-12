<?php

namespace Oro\BugBundle\Controller;

use Oro\BugBundle\Entity\Issue;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;
use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class IssueController extends Controller
{
    /**
     * @Route("/issue/index", name="bug.issue_index")
     * @Template
     * @Acl(
     *     id="bug.issue_view",
     *     type="entity",
     *     class="OroBugBundle:Issue",
     *     permission="VIEW"
     * )
     */
    public function indexAction()
    {
        return ['entity_class' => 'Oro\BugBundle\Entity\Issue'];
    }


    /**
     * @Route("/issue/create", name="bug.issue_create")
     * @Template("OroBugBundle:Issue:update.html.twig")
     * @Acl(
     *     id="bug.issue_create",
     *     type="entity",
     *     class="OroBugBundle:Issue",
     *     permission="CREATE"
     * )
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction()
    {
        return $this->update(new Issue());
    }


    /**
     * @Route("/issue/create/{id}", name="bug.issue_children_create", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template("OroBugBundle:Issue:update.html.twig")
     * @Acl(
     *     id="bug.issue_create",
     *     type="entity",
     *     class="OroBugBundle:Issue",
     *     permission="CREATE"
     * )
     * @param Issue $parentIssue
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createChildrenAction(Issue $parentIssue)
    {
        $issue = new Issue();
        $className = ExtendHelper::buildEnumValueClassName('oro_issue_type');
        $issue->setType($this->get('doctrine')->getManager()->getRepository($className)->find(Issue::TYPE_SUBTASK));
        $type = $issue->getType();
        $name = $type->getName();
        $issue->setParentIssue($parentIssue);

        return $this->update($issue, $parentIssue);
    }


    /**
     * @Route("/issue/update/{id}", name="bug.issue_update", requirements={"id":"\d+"}, defaults={"id":0})
     * @Template()
     * @Acl(
     *     id="bug.issue_update",
     *     type="entity",
     *     class="OroBugBundle:Issue",
     *     permission="EDIT"
     * )
     * @param Issue $issue
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Issue $issue)
    {
        return $this->update($issue, $issue->getParentIssue());
    }

    /**
     * @param Issue $issue
     * @param bool $parent
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function update(Issue $issue, $parent = null)
    {
        $saved = false;
        $request = $this->getRequest();
        $form = $parent ?
            $this->get('form.factory')->create('bug_children_issue', $issue, ['parentIssue' => $parent]) :
            $this->get('form.factory')->create('bug_issue', $issue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($issue);
            $entityManager->flush();

            if (!$this->getRequest()->get('_widgetContainer')) {
                return $this->get('oro_ui.router')->redirectAfterSave(
                    [
                        'route' => 'bug.issue_update',
                        'parameters' => ['id' => $issue->getId()],
                    ],
                    ['route' => 'bug.issue_index'],
                    $issue
                );
            }
            $saved = true;

        }

        return [
            'entity' => $issue,
            'form' => $form->createView(),
            'saved' => $saved,
        ];
    }

    /**
     * @Route("issue/view/{id}", name="bug.issue_view", requirements={"id"="\d+"})
     * @Template
     * @AclAncestor("bug.issue_view")
     * @param Issue $issue
     * @return array
     * @internal param Issue $vehicle
     */
    public function viewAction(Issue $issue)
    {
        return ['entity' => $issue];
    }


}

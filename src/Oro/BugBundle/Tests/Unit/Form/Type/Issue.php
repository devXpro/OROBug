<?php

namespace Oro\BugBundle\Tests\Unit\Form\Type;

class Issue extends \Oro\BugBundle\Entity\Issue
{
    /** @var IssueTypeStub */
    private $type;

    /**
     * @return IssueTypeStub
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param IssueTypeStub $type
     */
    public function setType(IssueTypeStub $type)
    {
        $this->type = $type;
    }
}

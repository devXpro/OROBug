<?php

namespace Oro\BugBundle\Tests\Unit\Entity;

use Oro\BugBundle\Entity\IssueStatus;

class IssueStatusTest extends AbstractEntityTestCase
{
    /** @var IssueStatus */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Oro\BugBundle\Entity\IssueStatus';
    }

    /**
     * {@inheritDoc}
     */
    public function getSetDataProvider()
    {
        return ['label' => ['label', 'label', 'label']];
    }
}


<?php

namespace Oro\BugBundle\Tests\Unit\Entity;

use Oro\BugBundle\Entity\IssuePriority;

class IssuePriorityTest extends AbstractEntityTestCase
{
    /** @var IssuePriority */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Oro\BugBundle\Entity\IssuePriority';
    }

    /**
     * {@inheritDoc}
     */
    public function getSetDataProvider()
    {
        return ['label' => ['label', 'label', 'label']];
    }
}


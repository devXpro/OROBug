<?php

namespace Oro\BugBundle\Tests\Unit\Entity;

use Oro\BugBundle\Entity\IssueResolution;

class IssueResolutionTest extends AbstractEntityTestCase
{
    /** @var IssueResolution */
    protected $entity;

    /**
     * {@inheritDoc}
     */
    public function getEntityFQCN()
    {
        return 'Oro\BugBundle\Entity\IssueResolution';
    }

    /**
     * {@inheritDoc}
     */
    public function getSetDataProvider()
    {
        return ['label' => ['label', 'label', 'label']];
    }
}


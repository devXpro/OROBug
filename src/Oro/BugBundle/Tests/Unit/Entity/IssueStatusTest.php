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
        return [
            'label' => ['label', 'label', 'label'],
            'open' => ['open', true, true],
        ];
    }

    public function testLabel()
    {
        $this->entity->setLabel('label');
        $this->assertEquals('label', $this->entity->getLabel());
        $this->assertEquals('label', $this->entity->__toString());
    }
}


<?php

namespace Oro\BugBundle\ImportExport\TemplateFixture;

use Oro\BugBundle\Entity\Issue;
use Oro\BugBundle\Entity\IssuePriority;
use Oro\BugBundle\Entity\IssueResolution;
use Oro\Bundle\ImportExportBundle\TemplateFixture\AbstractTemplateRepository;
use Oro\Bundle\ImportExportBundle\TemplateFixture\TemplateFixtureInterface;

class IssueFixture extends AbstractTemplateRepository implements TemplateFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEntityClass()
    {
        return 'Oro\BugBundle\Entity\Issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->getEntityData('Issue1');
    }

    /**
     * {@inheritdoc}
     */
    protected function createEntity($key)
    {
        return new Issue();
    }

    /**
     * @param string $key
     * @param object $entity
     */
    public function fillEntityData($key, $entity)
    {
        $user = $this->templateManager
            ->getEntityRepository('Oro\Bundle\UserBundle\Entity\User')
            ->getEntity('Elis Peris');
        $organization = $this->templateManager
            ->getEntityRepository('Oro\Bundle\OrganizationBundle\Entity\Organization')
            ->getEntity('default');

        $priority = new IssuePriority();
        $priority->setLabel('Hight');
        $resolution = new IssueResolution();
        $resolution->setLabel('Fixed');
        switch ($key) {
            case 'Issue-1':
                $entity->setSummary('Summary field');
                $entity->setDescription('Description field');
                $entity->setOrganization($organization);
                $entity->setPriority($priority);
                $entity->setResolution($resolution);
                $entity->setAssignee($user);
                $entity->setOwner($user);
                $entity->createdAt(new \DateTime());
                $entity->updatedAt(new \DateTime());

                return;
        }
        parent::fillEntityData($key, $entity);
    }
}
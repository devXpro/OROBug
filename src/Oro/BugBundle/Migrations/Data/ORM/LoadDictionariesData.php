<?php

namespace Oro\BugBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\BugBundle\Entity\Issue;
use Oro\BugBundle\Entity\IssueStatus;
use Oro\Bundle\EntityExtendBundle\Tools\ExtendHelper;

class LoadDictionariesData extends AbstractFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {

        $persist = function (array $labels, $name, $add = false) use ($manager) {
            $name = 'Oro\BugBundle\Entity\\'.$name;
            foreach ($labels as $label) {
                $entity = new $name();
                $entity->setLabel($label);
                if ($add && in_array($label, [IssueStatus::OPEN, IssueStatus::REOPEN])) {
                    $entity->setOpen(true);
                }
                $manager->persist($entity);

            }
        };

        $persist(
            [
                IssueStatus::OPEN,
                IssueStatus::REOPEN,
                'Needs Work',
                'Needs Review',
                'Done',
                'Patch',
                'Fixed',
                'Postponed',
                'Closed',
            ],
            'IssueStatus',
            true
        );
        $persist(
            ['Fixed', 'Duplicate', 'Won\'t fix', 'Incomplete', 'Cannot reproduce', 'Redundant', 'Invalid'],
            'IssueResolution'
        );
        $persist(['Highest', 'High', 'Medium', 'Low', 'Lowest'], 'IssuePriority');
        $manager->flush();
        $types = [
            Issue::TYPE_BUG => false,
            Issue::TYPE_STORY => false,
            Issue::TYPE_SUBTASK => false,
            Issue::TYPE_TASK => true,
        ];
        $type = ExtendHelper::buildEnumValueClassName('oro_issue_type');
        $typeRepo = $manager->getRepository($type);
        $priority = 1;
        foreach ($types as $id => $isDefault) {
            $enumOption = $typeRepo->createEnumValue('oro.bug.issue.type.'.$id, $priority++, $isDefault, $id);
            $manager->persist($enumOption);
        }
        $manager->flush();
    }
}

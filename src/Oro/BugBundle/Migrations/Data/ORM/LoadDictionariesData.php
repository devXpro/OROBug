<?php

namespace Oro\BugBundle\Migrations\Data\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\BugBundle\Entity\IssueStatus;

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

        return true;
    }
}

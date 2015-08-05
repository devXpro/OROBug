<?php

namespace Oro\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssuePrioritySelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_select_issue_priority';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => 'Oro\BugBundle\Entity\IssuePriority',
                'label'=>'oro.bug.issue.priority.label'
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }
}

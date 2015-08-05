<?php

namespace Oro\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueStatusSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_select_issue_status';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => 'Oro\BugBundle\Entity\IssueStatus',
                'label' => 'oro.bug.issue.status.label'
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

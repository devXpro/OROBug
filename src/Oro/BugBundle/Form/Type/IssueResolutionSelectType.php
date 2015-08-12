<?php

namespace Oro\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueResolutionSelectType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_select_issue_resolution';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => 'Oro\BugBundle\Entity\IssueResolution',
                'label' => 'oro.bug.issue.resolution.label',
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

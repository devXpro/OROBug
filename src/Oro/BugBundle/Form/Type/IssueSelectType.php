<?php

namespace Oro\BugBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueSelectType extends AbstractType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'bug_select_issue';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'class' => 'Oro\BugBundle\Entity\Issue',
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'entity';
    }
}

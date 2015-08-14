<?php

namespace Oro\BugBundle\Form\Type;

use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IssueType extends AbstractType
{
    /** @var  string */
    private $dataClass;

    /**
     */
    public function __construct()
    {
        $this->setDataClass('Oro\BugBundle\Entity\Issue');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */

        $builder
            ->add('summary', 'text', ['label' => 'oro.bug.issue.summary.label'])
            ->add('description', 'textarea', ['label' => 'oro.bug.issue.description.label'])
            ->add('type', 'bug_select_issue_type')
            ->add('priority', 'bug_select_issue_priority', ['required' => true])
            ->add('status', 'bug_select_issue_status')
            ->add('resolution', 'bug_select_issue_resolution')
            ->add('assignee', 'oro_user_select', ['required' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_issue';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => $this->getDataClass(),
            ]
        );
    }

    /**
     * @param mixed $dataClass
     */
    public function setDataClass($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    /**
     * @return mixed
     */
    public function getDataClass()
    {
        return $this->dataClass;
    }
}

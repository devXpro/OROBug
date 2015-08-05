<?php

namespace Oro\BugBundle\Form\Type;

use Oro\Bundle\SecurityBundle\SecurityFacade;
use Oro\Bundle\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @SuppressWarnings(PHPMD.ElseExpression)
 * Class IssueType
 * @package BugBundle\Form\Type
 */
class IssueType extends AbstractType
{

    /** @var User */
    private $user;

    /**
     * @param SecurityFacade $token
     */
    public function __construct(SecurityFacade $token)
    {
        /** @var User user */
        $this->user = $token->getToken()->getUser();
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
            ->add('priority', 'bug_select_issue_priority')
            ->add('status', 'bug_select_issue_status')
            ->add('resolution', 'bug_select_issue_resolution')
            ->add('assignee', 'oro_user_select');

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
            array(
                'data_class' => 'Oro\BugBundle\Entity\Issue',
            )
        );


    }
}

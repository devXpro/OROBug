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
class ChildrenIssueType extends AbstractType
{

//    /** @var User */
//    private $user;
//
//    /**
//     * @param SecurityFacade $token
//     */
//    public function __construct(SecurityFacade $token)
//    {
//        /** @var User user */
//        $this->user = $token->getToken()->getUser();
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */

        $builder->remove('type');
        $builder->add(
            'parentIssue',
            'bug_parent_issue_select',
            [
                'data' => $options['parentIssue'],
                'empty_data' => $options['parentIssue']->getId(),
            ]
        );

    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_children_issue';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'bug_issue';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {


        $resolver->setRequired(['parentIssue']);
//        $resolver->setAllowedValues(
//            'parentIssue',
//            function ($value) {
//                if ($value instanceof Issue || $value == null) {
//                    return true;
//                } else {
//                    return false;
//                }
//            }
//        );
    }
}

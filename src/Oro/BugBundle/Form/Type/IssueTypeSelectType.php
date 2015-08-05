<?php

namespace Oro\BugBundle\Form\Type;

use Oro\BugBundle\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class IssueTypeSelectType extends AbstractType
{
    /** @var TranslatorInterface */
    protected $trans;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->trans = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bug_select_issue_type';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'enum_code' => 'oro_issue_type',
                'property' => 'name',
                'label' => 'oro.bug.issue.type.label',
            ]
        );
        $resolver->setNormalizers(
            [
                'choice_list' => function (Options $options, ObjectChoiceList $choiceObj) {

                    $choices = $choiceObj->getChoices();
                    foreach ($choices as $key => $choice) {
                        if ($choice->getId() == Issue::TYPE_SUBTASK) {
                            unset($choices[$key]);
                        } else {
                            $choice->setName($this->trans->trans($choice->getName()));
                        }
                    }
                    $options;

                    return new ObjectChoiceList($choices);
                },
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'oro_enum_select';
    }
}

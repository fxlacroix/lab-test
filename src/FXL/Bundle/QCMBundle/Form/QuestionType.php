<?php

namespace FXL\Bundle\QCMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content')
            ->add('time')
            ->add('isOpened')
            ->add('node')
            ->add('url')
            ->add('file')
            ->add('path', null, array(
                'label_attr'=>array(
                    'style' => 'display: none;'
                ),
                'read_only' =>  true
            ))
            ->add('answers', 'collection', array(
                'type'      => new AnswerType(),
                'allow_add'    => true,
                'allow_delete' => true,
            ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\QCMBundle\Entity\Question',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_bundle_qcmbundle_questiontype';
    }
}

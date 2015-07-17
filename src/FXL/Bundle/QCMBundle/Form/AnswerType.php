<?php

namespace FXL\Bundle\QCMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content')
            ->add('right', null, array(
                "required" => false
            ))
            ->add('file')
            ->add('url')
            ->add('path', null, array(
                'label_attr'=>array(
                    'style' => 'display: none;'
                ),
                'read_only' =>  true
            ))
            ->add('question')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\QCMBundle\Entity\Answer',
            'csrf_protection' => false,

        ));
    }

    public function getName()
    {
        return 'fxl_bundle_qcmbundle_answertype';
    }
}

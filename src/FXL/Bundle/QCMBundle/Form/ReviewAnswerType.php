<?php

namespace FXL\Bundle\QCMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReviewAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden')
            ->add('selected', 'checkbox', array(
                'required'      => false,
                'label_attr'    =>  array(
                    'style' =>  'display: none;'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\QCMBundle\Entity\Answer'

        ));
    }

    public function getName()
    {
        return 'fxl_bundle_qcmbundle_reviewanswertype';
    }
}

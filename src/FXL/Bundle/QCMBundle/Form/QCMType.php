<?php

namespace FXL\Bundle\QCMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QCMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('email')
            ->add('time')
            ->add('url')
            ->add('file')
            ->add('path', null, array(
                'label_attr'=>array(
                    'style' => 'display: none;'
                ),
                'read_only' =>  true
            ))
            ->add('published')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\QCMBundle\Entity\QCM',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_bundle_qcmbundle_qcmtype';
    }
}

<?php

namespace FXL\Bundle\QCMBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrepareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' =>  'Veuillez saisir un nom',
                'required'  =>  true
            ))
            ->add('email', null, array(
                'label' =>  'Un email pour recevoir le résultat',
                'required'  =>  false

            ))
            ->add('selectionQuestion', null, array(
                'label'     => 'Combien de questions ?',
                'attr'    =>  array(
                    'style' =>  'display: none;'
                )

            ))
            ->add('qcm', null, array(
                'attr'  => array(
                    'style' =>  'display: none;'
                ),
                'label_attr'    =>  array(
                    'style' =>  'display: none;'
                )
            ))
            ->add('captcha', 'captcha', array(
                'label' =>  'Veuillez saisir le code de sécurité'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\QCMBundle\Entity\Review'
        ));
    }

    public function getName()
    {
        return 'fxl_bundle_qcmbundle_preparetype';
    }
}

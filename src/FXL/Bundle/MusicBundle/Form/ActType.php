<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number')
            ->add('name')
            ->add('description')
            ->add('project', null, array(
                "attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                "label_attr"      =>  array(
                    "style" =>  "display: none;"
                ),

            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Act',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_acttype';
    }
}

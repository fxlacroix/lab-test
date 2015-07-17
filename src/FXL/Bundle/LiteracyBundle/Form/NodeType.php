<?php

namespace FXL\Bundle\LiteracyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add("parent", null, array(
                "attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                "label_attr"      =>  array(
                    "style" =>  "display: none;"
                ),

            ))
            ->add("user", null, array(
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
            'data_class' => 'FXL\Bundle\LiteracyBundle\Entity\Node',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'fxl_bundle_LiteracyBundle_nodetype';
    }
}

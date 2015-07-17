<?php

namespace FXL\Bundle\LiteracyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options["text-editor"]) {

            $builder
                ->add('content')
            ;
        } else {

            $builder
                ->add('name')
                ->add('description')
                ->add('position')
                ->add("group", null, array(
                    "attr"      =>  array(
                        "style" =>  "display: none;"
                    ),
                    "label_attr"      =>  array(
                        "style" =>  "display: none;"
                    ),
                ))
            ;
        }

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\LiteracyBundle\Entity\Sheet',
            'csrf_protection' => false,
            'text-editor'     => false
        ));
    }

    public function getName()
    {
        return 'fxl_bundle_LiteracyBundle_sheettype';
    }
}

<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FXL\Bundle\MusicBundle\Entity\Asset;

class AssetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('type', 'choice', array(
                'choices'   => Asset::getTypeAssets(),
                'required'  => false,
            ))
            ->add('file')
            ->add('article', null, array(
                'empty_value' => "",
                'required'  => false,
                "label"  => " ",
                "attr"   => array(
                    "style" =>  "display: none;"
                ),
            ))
            ->add('project', null, array(
                'required'  => false,
                'empty_value' => "",
                "label"  => " ",
                "attr"   => array(
                    "style" =>  "display: none;"
                ),
            ))
            ->add('track', null, array(
                'required'  => false,
                'empty_value' => "",
                "label"  => " ",
                "attr"   => array(
                    "style" =>  "display: none;"
                ),
            ))
            ->add('author', null, array(
                'required'  => false,
                'empty_value' => "",
                "label"  => " ",
                "attr"   => array(
                    "style" =>  "display: none;"
                ),
            ))
            ->add('description')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Asset',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_assettype';
    }
}

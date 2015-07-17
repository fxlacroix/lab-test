<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('number')
            ->add('description')
            ->add('content', 'textarea', array(
                'required' => false,
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'simple' // simple, advanced, bbcode
            )))
            ->add('published')
            ->add('project', null, array(
                "label"  => " ",
                "attr"   => array(
                    "style" =>  "display: none;"
                ),
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Article',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_articletype';
    }
}

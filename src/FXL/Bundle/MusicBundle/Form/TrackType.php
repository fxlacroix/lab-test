<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class TrackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('number')
            ->add('project', null, array(
                "attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                "label_attr"      =>  array(
                    "style" =>  "display: none;"
                ),

            ))

            ->add('description')
             ->add('content', 'textarea', array(
                'required' => false,
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'simple' // simple, advanced, bbcode
            )))
            ->add('chords')
            ->add('authors', null, array(
                'required'      => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder("a")
                        ->orderBy("a.name", "asc")
                        ;
                },
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Track',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_tracktype';
    }
}

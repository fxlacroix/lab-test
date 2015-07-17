<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('parent', null, array(
                "required"      => false,
                "empty_value"    => " ",
                'query_builder' => function(EntityRepository $er) {

                    return $er->createQueryBuilder('t')
                        ->select("t")
                        ->where("t.parent is null")
                        ->orderBy("t.name", "asc")
                        ;
                },
            ))
            ->add('file')
            ->add('path', 'text', array(
                'read_only' => true,
            ))
            ->add('projects', null, array(
                'attr'   =>  array(
                    'class' =>  'display-none'
                ),
                'label_attr'    =>  array(
                    'class' =>  'display-none'
                )
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Tag',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_tagtype';
    }
}

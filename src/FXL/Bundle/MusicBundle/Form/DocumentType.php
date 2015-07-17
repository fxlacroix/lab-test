<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('label', false);

        $builder
            ->add('name')
            ->add("updatedAt", 'date', array(
                'required'  => false,
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('version')
            ->add('description')
            ->add('track', null)
            ->add('file')
            ->add('path', 'text', array(
                'required' => false
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Document',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_documenttype';
    }
}

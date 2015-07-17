<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('project')
            ->add('scenes')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Actor',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_actortype';
    }
}

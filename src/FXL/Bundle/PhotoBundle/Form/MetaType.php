<?php

namespace FXL\Bundle\PhotoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MetaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('hook'  , 'textarea' , array('label' => 'Phrase d\'accrochage',))
            ->add('title1', 'textarea' , array('label' => 'Titre à gauche'))
            ->add('text1' , 'textarea' , array('label' => 'Texte à gauche', 'attr' => array('cols' => '5', 'rows' => '5'),))
            ->add('title2', 'textarea' , array('label' => 'Titre au milieu'))
            ->add('text2' , 'textarea' , array('label' => 'Texte au milieu', 'attr' => array('cols' => '5', 'rows' => '5')))
            ->add('title3', 'textarea' , array('label' => 'Texte à droite'))
            ->add('text3' , 'textarea' , array('label' => 'Texte à droite', 'attr' => array('cols' => '5', 'rows' => '5')))
            ->add('submit', 'submit', array('attr'  => array('class' => 'pull-right')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\PhotoBundle\Entity\Meta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'fxl_bundle_photobundle_meta';
    }
}

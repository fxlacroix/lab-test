<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lookup', null, array(
                'required' => false
            ))
            ->add('wikipediaId', null, array(
                'disabled'  => true
            ))
            ->add('name')
            ->add('description')
            ->add('authors_lookup', 'entity', array(
                'class' => 'FXLMusicBundle:Author',
                'label' => 'Existing author',
                'multiple'  => true,
                'required'  => false,
                'query_builder' => function(EntityRepository $er) {

                    return $er->createQueryBuilder("a")
                        ->orderBy("a.name", "asc")
                        ;
                },
            ))
            ->add('tracks', 'entity', array(
                'class'         => "FXLMusicBundle:Track",
                'multiple'      => true,
                'required'      => false,
                'query_builder' => function(EntityRepository $er) use ($options) {
                    $queryBuilder = $er->createQueryBuilder("t")
                        ->select("t")
                        ->orderBy("t.name")
                        ;
                    if($options['project']) {
                        $queryBuilder
                        ->where("t.project = :project")
                        ->setParameter('project', $options['project']);
                    }

                    return $queryBuilder;
                },
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Author',
            'csrf_protection' => false,
            'project'    => null
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_authortype';
    }
}

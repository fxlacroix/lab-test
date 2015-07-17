<?php

namespace FXL\Bundle\MusicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use FXL\Bundle\MusicBundle\Form\TrackType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('type', 'choice', array(
               'choices'   => array(
                    'music'   => 'Music',
                    'literacy' => 'Literacy'
                 ),
                "attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                "label_attr"      =>  array(
                    "style" =>  "display: none;"
                ),
            ))
            ->add('user', null, array(
                  "attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                "label_attr"      =>  array(
                    "style" =>  "display: none;"
                )
            ))
            ->add('authors', null, array(
                "attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                "label_attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                'required'  =>  false,
                'query_builder' => function(EntityRepository $er) {

                    return $er->createQueryBuilder("fxl")
                        ->select("a")
                        ->from("FXLMusicBundle:Author", "a")
                        ->innerJoin("a.user", "u")
                        ->orderBy("a.name", "asc")
                        ;
                },
            ))

            ->add('tags', null, array(
                'required'  =>  false,
                'query_builder' => function(EntityRepository $er) use($options) {

                    $queryBuilder = $er->createQueryBuilder('t')
                        ->select("t")
                        ->leftJoin("t.parent", "tp")
                        ->orderBy("t.name", "asc")
                        ;

                    if($options['project_type']) {

                        $queryBuilder->andWhere("tp.name = :tpvalue")
                        ->setParameter("tpvalue", $options['project_type']);
                    }


                    return $queryBuilder;

                },
            ))
            ->add('description')
            ->add('content', 'textarea', array(
                'required' => false,
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'simple' // simple, advanced, bbcode
            )))
            ;
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\MusicBundle\Entity\Project',
            'csrf_protection' => false,
            'project_type'  =>  null,
            'user'          =>  null
        ));
    }

    public function getName()
    {
        return 'fxl_musicbundle_projecttype';
    }
}

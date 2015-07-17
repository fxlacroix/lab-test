<?php

namespace FXL\Bundle\LiteracyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('file')
            ->add("parent", "entity", array(
                "class"     =>  "FXLLiteracyBundle:Group",
                "empty_value"=> "",
                "attr"      =>  array(
                    "style" =>  "display: none;"
                ),
                "label_attr"      =>  array(
                    "style" =>  "display: none;"
                ))
            )

            ->add('node', null, array(
                "empty_value"=> "",
            "attr"      =>  array(
                "style" =>  "display: none;"
            ),
            "label_attr"      =>  array(
                "style" =>  "display: none;"
            )));

        if($options["type"] == "create") {

            $builder
                ->add('structure', "entity", array(

                'empty_value' => 'Choisissez une structure existante',
                'class'     => 'FXLLiteracyBundle:Group',
                'property' => 'name',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('g')
                        ->where("g.published = :published")
                        ->setParameter("published", true)
                        ->orderBy('g.name', 'ASC');
                    },
                )

            );
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FXL\Bundle\LiteracyBundle\Entity\Group',
            'csrf_protection' => false,
            'type'          => "create"
        ));
    }

    public function getName()
    {
        return 'fxl_bundle_LiteracyBundle_grouptype';
    }
}

<?php

namespace FXL\Bundle\PhotoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array('label' => 'Votre email'));
        $builder->add('subject', 'text', array('label' => 'Objet'));
        $builder->add('message', 'textarea', array('data' => 'Votre nom:
Votre prénom:
Addresse d\'envoi:
Dossier et image choisie:'));
    }

    public function getName()
    {
        return 'contact';
    }
}

<?php

namespace LivreBundle\Form;

use Doctrine\ORM\Mapping\Entity;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use UserBundle\Entity\User;


class RechercheISBNLivreType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isbn', null, array(
            'attr' => array('maxlength' => 13),
        ))->add('lieu', AjoutLieuLivreType::class, array(
            'label' => false
        ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_rechercheIBSNLivre';
    }


}

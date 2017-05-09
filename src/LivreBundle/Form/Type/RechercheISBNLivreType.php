<?php

namespace LivreBundle\Form\Type;

use Doctrine\ORM\Mapping\Entity;

use LivreBundle\Form\Type\AjoutLieuLivreType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'lieu'  => null,
        ));
    }


}

<?php

namespace LivreBundle\Form\Type;

use LivreBundle\Entity\Meuble;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class EtagereType extends AbstractType
{
    /**
     * @var null|TokenStorageInterface
     */
    protected $tokenStorage = null;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, array('label' => 'Nom'))
            ->add('meuble', EntityType::class, array(
                'choices'      => $this->tokenStorage->getToken()->getUser()->getMeubles(),
                'choice_label' => 'nom',
                'class'        => Meuble::class,
                'empty_data'   => null,
                'placeholder'  => "Meuble de l'étagère"
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LivreBundle\Entity\Etagere'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_etagere';
    }


}

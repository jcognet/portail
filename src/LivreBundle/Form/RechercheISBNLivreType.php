<?php

namespace LivreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RechercheISBNLivreType extends AbstractType
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
        $builder->add('isbn', null, array(
            'attr' => array('maxlength' => 13),
        ))->add(
            'lieu', ChoiceType::class, array(
                'mapped'   => false,
                'expanded' => false,
                'multiple' => false,
                'label'    => 'lieu',
                'choices'  => $this->tokenStorage->getToken()->getUser()->getReferentielLieu(),
            )
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_rechercheIBSNLivre';
    }


}

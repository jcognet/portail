<?php

namespace LivreBundle\Form;

use LivreBundle\Entity\Piece;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MeubleType extends AbstractType
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
        $builder->add('nom', null, array('label'=>'Nom'))
            ->add('piece', EntityType::class, array(
                'choices'      => $this->tokenStorage->getToken()->getUser()->getPieces(),
                'choice_label' => 'nom',
                'class'        => Piece::class,
                'empty_data'   => null,
                'placeholder'  => "Pièce du meuble"
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LivreBundle\Entity\Meuble'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_meuble';
    }


}

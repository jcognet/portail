<?php

namespace LivreBundle\Form;

use LivreBundle\Entity\Maison;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PieceType extends AbstractType
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
        $builder->add('nom')
            ->add('etage')
            ->add('maison', EntityType::class, array(
                'choices'      => $this->tokenStorage->getToken()->getUser()->getMaisons(),
                'choice_label' => 'nom',
                'class'        => Maison::class,
                'empty_data'   => null,
                'placeholder'  => "Maison de la pièce"
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'LivreBundle\Entity\Piece'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_piece';
    }


}

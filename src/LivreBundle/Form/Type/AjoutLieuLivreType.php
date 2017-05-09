<?php

namespace LivreBundle\Form\Type;

use LivreBundle\Service\LieuService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use LivreBundle\Entity\Etagere;
use LivreBundle\Entity\Maison;
use LivreBundle\Entity\Meuble;
use LivreBundle\Entity\Piece;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AjoutLieuLivreType extends AbstractType
{
    /**
     * @var LieuService|null
     */
    protected $lieuService = null;

    public function __construct(LieuService $lieuService, TokenStorageInterface $tokenStorage)
    {
        $this->lieuService  = $lieuService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user  = $this->tokenStorage->getToken()->getUser();
        $livre = $options{'livre'};
        $lieu  = null;
        if (false === is_null($livre)) {
            $lieu = $livre->getLieu();
        }elseif (false === $options{'lieu'}) {
            $lieu =  $options{'lieu'};
        }
        $builder->add(
                'lieu', ChoiceType::class, array(
                    'placeholder'  => '',
                    'data'         => $lieu,
                    'empty_data'   => null,
                    'label'        => false,
                    'mapped'       => false,
                    'expanded'     => false,
                    'multiple'     => false,
                    'choices'      => $this->tokenStorage->getToken()->getUser()->getReferentielLieu(),
                    'choice_label' => function ($lieu, $key, $index) {
                        /** @var Category $category */
                        return $lieu->getNom();
                    },
                    'choice_attr'  => function ($val, $key, $index) {
                        return [
                            'data-type' => strtolower((new \ReflectionClass($val))->getShortName()),
                            'data-id'   => $val->getId()
                        ];
                    },
                    'attr'         => array(
                        'class' => 'ddl_lieu_main'
                    )
                )
            )
            ->add('maison', EntityType::class, array(
                'class'        => Maison::class,
                'choices'      => $user->getMaisons(),
                'choice_label' => 'nom',
                'empty_data'   => null,
                'label'        => false,
                'placeholder'  => '',
                'attr'         => array(
                    'class'     => 'ddl_lieu_class hidden',
                    'data-type' => 'maison'
                )
            ))
            ->add('piece', EntityType::class, array(
                'class'        => Piece::class,
                'choices'      => $user->getPieces(),
                'choice_label' => 'nom',
                'empty_data'   => null,
                'label'        => false,
                'placeholder'  => '',
                'attr'         => array(
                    'class'     => 'ddl_lieu_class hidden',
                    'data-type' => 'piece'
                )
            ))
            ->add('meuble', EntityType::class, array(
                'class'        => Meuble::class,
                'choices'      => $user->getMeubles(),
                'choice_label' => 'nom',
                'empty_data'   => null,
                'label'        => false,
                'placeholder'  => '',
                'attr'         => array(
                    'class'     => 'ddl_lieu_class hidden',
                    'data-type' => 'meuble'
                )
            ))
            ->add('etagere', EntityType::class, array(
                'class'        => Etagere::class,
                'choices'      => $user->getEtageres(),
                'choice_label' => 'nom',
                'empty_data'   => null,
                'label'        => false,
                'placeholder'  => '',
                'attr'         => array(
                    'class'     => 'ddl_lieu_class hidden',
                    'data-type' => 'etagere'
                )
            ));
        // Mise en place des données
        if (false === is_null($lieu)) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($lieu) {
                $form = $event->getForm();
                // On impose la valeur dans les champs cachés
                $class = strtolower((new \ReflectionClass($lieu))->getShortName());
                switch ($class) {
                    case 'maison':
                        $form->get('maison')->setData($lieu);
                        break;
                    case 'piece':
                        $form->get('piece')->setData($lieu);
                        break;
                    case 'meuble':
                        $form->get('meuble')->setData($lieu);
                        break;
                    case 'etagere':
                        $form->get('etagere')->setData($lieu);
                        break;
                    default:
                        throw new \Exception("Type inconnu : " . get_class($lieu));
                }
            });
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => false,
            'livre'  => null,
            'lieu'  => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_ajoutelieulivre';
    }


}

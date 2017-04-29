<?php

namespace LivreBundle\Form;

use LivreBundle\Service\LieuService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuType extends AbstractType
{
    /**
     * Constante avec le nom du champ du type de lieu
     */
    const TYPE_LIEU_NAME ='typeLieu';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(self::TYPE_LIEU_NAME, ChoiceType::class, array(
                'choices' => LieuService::getTypesLieux(),
                'label'   => 'Type de lieux ',
                'placeholder'=>'Choisir le type de lieu',
                'choice_label'=>function ($value, $key, $index) {
                    return ucfirst($key);
                }
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'mapped' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_lieu';
    }


    /**
     * Retourne le namespace courant
     * @return string
     */
    public static function getCurrentNamespace(){
        return __NAMESPACE__ ;
    }

}

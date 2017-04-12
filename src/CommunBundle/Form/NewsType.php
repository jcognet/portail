<?php

namespace CommunBundle\Form;

use CommunBundle\Entity\News;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('corps', CKEditorType::class, array(
                'label'    => 'Corps',
                'required' => true
            ))
            ->add('type', ChoiceType::class, array(
                    'label'      => 'Type de news',
                    'required'   => false,
                    'choices'    => array_flip(News::getListeTypeNews()),
                    'empty_data' => '',
                )

            )
            ->add('dateMiseEnLigne');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CommunBundle\Entity\News'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'communbundle_news';
    }


}

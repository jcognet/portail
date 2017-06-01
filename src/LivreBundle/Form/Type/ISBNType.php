<?php

namespace LivreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ISBNType extends AbstractType
{

    private static $premiereInstance = false;

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ISBN', null, array(
                'label' => 'ISBN',
                'attr'  => array(
                    'class' => 'isbn_input'
                )
            ));
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'livrebundle_isbn';
    }


    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $options['affiche_js'] = self::$premiereInstance;
        parent::buildView($view, $form, $options);
        self::$premiereInstance = true;
    }

}

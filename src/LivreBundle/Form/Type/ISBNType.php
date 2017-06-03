<?php

namespace LivreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ISBNType extends AbstractType
{

    /**
     * Indique si une instance du champ a déjà été créé
     * @var bool
     */
    private static $premiereInstance = true;

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
        parent::buildView($view, $form, $options);
        $view->vars['affiche_js'] = self::$premiereInstance;
        self::$premiereInstance = false;
    }

}

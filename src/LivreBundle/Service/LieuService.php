<?php

namespace LivreBundle\Service;

use LivreBundle\Form\LieuType;


/**
 * Class LieuService
 * @package LivreBundle\Service
 */
class LieuService
{

    /**
     * Constante de type de lieu maison
     */
    const TYPE_LIEU_MAISON = 'maison';

    /**
     * Constante de type de lieu pièce
     */
    const TYPE_LIEU_PIECE = 'piece';

    /**
     * Constante de type de lieu meuble
     */
    const TYPE_LIEU_MEUBLE = 'meuble';


    /**
     * Constante de type de lieu etagere
     */
    const TYPE_LIEU_ETAGERE = 'etagere';

    /**
     * Retourne la liste des types de lieu
     * @return array
     */
    public static function getTypesLieux()
    {
        return array(
            'maison'  => self::TYPE_LIEU_MAISON,
            'pièce'   => self::TYPE_LIEU_PIECE,
            'meuble'  => self::TYPE_LIEU_MEUBLE,
            'etagère' => self::TYPE_LIEU_ETAGERE,
        );
    }

    /**
     * Retourne le form type à partir d'un label type de lieu
     * @param $typeLieu
     * @return string
     */
    public function getFormFromTypeLieu($typeLieu)
    {
        $typeLieuPropre = ucfirst(strtolower($typeLieu));
        return LieuType::getCurrentNamespace() . "\\" . $typeLieuPropre . "Type";
    }
}


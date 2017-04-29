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
     * Retourne la liste des types de lieu
     * @return array
     */
    public static function getTypesLieux()
    {
        return array(
            'maison' => self::TYPE_LIEU_MAISON
        );
    }


    public function getFormFromTypeLieu($typeLieu){
        $typeLieuPropre = ucfirst(strtolower($typeLieu));
        return LieuType::getCurrentNamespace()."\\".$typeLieuPropre."Type";
    }
}


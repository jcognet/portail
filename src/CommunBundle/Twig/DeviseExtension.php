<?php
/**
 * Created by PhpStorm.
 * User: Jerome
 * Date: 12/11/2016
 * Time: 11:09
 */

namespace CommunBundle\Twig;


use CommunBundle\Entity\Devise;

class DeviseExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('affiche_prix', array($this, 'affichePrix')),
        );
    }

    /**
     * Affiche le prix d'une devise
     * @param $valeur
     * @return float
     */
    public function affichePrix($valeur)
    {
        $number = number_format(round($valeur, 2, PHP_ROUND_HALF_DOWN), 2, ',', ' ');
        // Suppression de ,00 final si nécessaire
        if (substr($number, -2) == '00') {
            $number = substr($number, 0, strlen($number) - 3);
        }
        return $number;
    }

}